<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\InventoryUnit;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $dates = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $start = $dates['start_date'] ?? now()->startOfMonth()->toDateString();
        $end = $dates['end_date'] ?? now()->endOfMonth()->toDateString();

        if ($start > $end) {
            throw ValidationException::withMessages(['end_date' => 'Tanggal akhir harus setelah tanggal mulai.']);
        }

        $sales = Sale::where('status', 'paid')
            ->whereBetween('paid_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->with('items');
        $paidSales = $sales->get();
        $revenue = (float) $paidSales->sum('total_amount');
        $costOfGoods = (float) $paidSales->flatMap->items
            ->sum(fn ($item) => (float) $item->average_unit_cost * $item->quantity);
        $operationalExpenses = (float) CashFlow::where('type', 'expense')
            ->where('category', '!=', 'stock_purchase')
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->sum('amount');
        $byProduct = SaleItem::query()
            ->select([
                'product_variant_id',
                DB::raw('SUM(quantity) as units_sold'),
                DB::raw('SUM(unit_price * quantity) as revenue'),
                DB::raw('SUM(COALESCE(average_unit_cost, 0) * quantity) as cost_of_goods_sold'),
            ])
            ->whereHas('sale', fn ($query) => $query->where('status', 'paid')
                ->whereBetween('paid_at', [$start.' 00:00:00', $end.' 23:59:59']))
            ->with('variant.product')
            ->groupBy('product_variant_id')
            ->get()
            ->map(function (SaleItem $item): array {
                $variant = $item->variant;
                $revenue = (float) $item->revenue;
                $cost = (float) $item->cost_of_goods_sold;

                return [
                    'product' => $variant->product->name,
                    'capacity_gb' => $variant->capacity_gb,
                    'color' => $variant->color,
                    'units_sold' => (int) $item->units_sold,
                    'revenue' => $revenue,
                    'cost_of_goods_sold' => $cost,
                    'gross_profit' => $revenue - $cost,
                ];
            })
            ->sortByDesc('units_sold')
            ->values();
        $inventory = InventoryUnit::where('status', 'in_stock');

        return response()->json([
            'period' => ['start_date' => $start, 'end_date' => $end],
            'paid_transactions' => $paidSales->count(),
            'units_sold' => $paidSales->flatMap->items->sum('quantity'),
            'revenue' => $revenue,
            'cost_of_goods_sold' => $costOfGoods,
            'gross_profit' => $revenue - $costOfGoods,
            'operating_expenses' => $operationalExpenses,
            'net_profit' => $revenue - $costOfGoods - $operationalExpenses,
            'units_in_stock' => (clone $inventory)->count(),
            'inventory_value' => (float) (clone $inventory)->sum('purchase_cost'),
            'by_product' => $byProduct,
        ]);
    }
}

