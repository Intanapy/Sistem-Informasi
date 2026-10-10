<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\InventoryUnit;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $start = now()->startOfDay();
        $end = now()->endOfDay();
        $sales = Sale::query();
        $paidSales = (clone $sales)->where('status', 'paid')->whereBetween('paid_at', [$start, $end]);
        $revenue = (float) (clone $paidSales)->sum('total_amount');
        $costOfGoods = (float) (clone $paidSales)->with('items')->get()
            ->flatMap->items
            ->sum(fn ($item) => (float) $item->average_unit_cost * $item->quantity);
        $operatingExpenses = (float) CashFlow::where('type', 'expense')
            ->where('category', '!=', 'stock_purchase')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        return response()->json([
            'date' => $start->toDateString(),
            'revenue' => $revenue,
            'cost_of_goods_sold' => $costOfGoods,
            'operating_expenses' => $operatingExpenses,
            'net_profit' => $revenue - $costOfGoods - $operatingExpenses,
            'paid_sales_count' => (clone $paidSales)->count(),
            'pending_sales_count' => (clone $sales)->where('status', 'pending')->whereBetween('created_at', [$start, $end])->count(),
            'units_sold' => SaleItem::whereHas('sale', fn ($query) => $query->where('status', 'paid')->whereBetween('paid_at', [$start, $end]))->sum('quantity'),
            'units_in_stock' => InventoryUnit::where('status', 'in_stock')->count(),
        ]);
    }
}

