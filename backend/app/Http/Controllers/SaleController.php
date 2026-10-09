<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\InventoryUnit;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(): JsonResponse
    {
        $sales = Sale::with(['employee:id,name', 'items.variant.product'])
            ->latest()
            ->paginate(20);

        return response()->json($sales);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'payment_method' => ['required', 'in:cash,transfer,card,qris'],
            'status' => ['required', 'in:pending,paid'],
        ]);

        $sale = DB::transaction(function () use ($request, $data): Sale {
            $variant = ProductVariant::whereKey($data['product_variant_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->firstOrFail();

            $sale = Sale::create([
                'number' => 'IST-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => $request->user()->id,
                'status' => $data['status'],
                'payment_method' => $data['payment_method'],
                'total_amount' => $variant->selling_price * $data['quantity'],
                'paid_at' => $data['status'] === 'paid' ? now() : null,
            ]);

            $item = $sale->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => $data['quantity'],
                'unit_price' => $variant->selling_price,
            ]);

            if ($sale->status === 'paid') {
                $this->deductInventoryAndRecordIncome($sale, $item);
            }

            return $sale;
        });

        return response()->json($sale->load(['employee:id,name', 'items.variant.product']), 201);
    }

    public function confirmPayment(Request $request, Sale $sale): JsonResponse
    {
        abort_unless($sale->user_id === $request->user()->id || $request->user()->isOwner(), 403);

        DB::transaction(function () use ($sale): void {
            $sale = Sale::whereKey($sale->id)->lockForUpdate()->firstOrFail();

            if ($sale->status === 'paid') {
                return;
            }

            foreach ($sale->items as $item) {
                $this->deductInventoryAndRecordIncome($sale, $item);
            }

            $sale->update(['status' => 'paid', 'paid_at' => now()]);
        });

        return response()->json($sale->fresh()->load(['employee:id,name', 'items.variant.product']));
    }

    private function deductInventoryAndRecordIncome(Sale $sale, SaleItem $item): void
    {
        // Harga pokok memakai rata-rata tertimbang seluruh unit yang tersedia.
        $availableUnits = InventoryUnit::where('product_variant_id', $item->product_variant_id)
            ->where('status', 'in_stock')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        if ($availableUnits->count() < $item->quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Stok tidak mencukupi untuk menyelesaikan transaksi.',
            ]);
        }

        $averageCost = $availableUnits->avg(fn (InventoryUnit $unit) => (float) $unit->purchase_cost);
        $item->update(['average_unit_cost' => $averageCost]);

        foreach ($availableUnits->take($item->quantity) as $unit) {
            $unit->update([
                'status' => 'sold',
                'sale_id' => $sale->id,
                'sale_item_id' => $item->id,
            ]);
        }

        CashFlow::firstOrCreate(
            ['sale_id' => $sale->id],
            [
                'user_id' => $sale->user_id,
                'type' => 'income',
                'category' => 'sale',
                'amount' => $sale->total_amount,
                'description' => 'Pembayaran transaksi '.$sale->number,
            ],
        );
    }
}
