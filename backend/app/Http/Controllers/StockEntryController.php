<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\InventoryUnit;
use App\Models\ProductVariant;
use App\Models\StockEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StockEntryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            StockEntry::with(['employee:id,name', 'variant.product', 'units:id,stock_entry_id,imei'])
                ->latest()->paginate(20),
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'unit_cost' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'imeis' => ['required', 'array', 'size:'.$request->input('quantity')],
            'imeis.*' => [
                'required', 'digits:15', 'distinct',
                Rule::unique('inventory_units', 'imei'),
            ],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $entry = DB::transaction(function () use ($request, $data): StockEntry {
            $entry = StockEntry::create([
                'number' => 'STK-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => $request->user()->id,
                'product_variant_id' => $data['product_variant_id'],
                'quantity' => $data['quantity'],
                'unit_cost' => $data['unit_cost'],
                'total_cost' => $data['quantity'] * $data['unit_cost'],
                'note' => $data['note'] ?? null,
            ]);

            foreach ($data['imeis'] as $imei) {
                InventoryUnit::create([
                    'product_variant_id' => $entry->product_variant_id,
                    'stock_entry_id' => $entry->id,
                    'imei' => $imei,
                    'purchase_cost' => $entry->unit_cost,
                    'status' => 'in_stock',
                ]);
            }

            CashFlow::create([
                'user_id' => $request->user()->id,
                'stock_entry_id' => $entry->id,
                'type' => 'expense',
                'category' => 'stock_purchase',
                'amount' => $entry->total_cost,
                'description' => 'Pembelian stok '.$entry->number,
            ]);

            return $entry;
        });

        return response()->json($entry->load(['employee:id,name', 'variant.product', 'units']), 201);
    }
}
