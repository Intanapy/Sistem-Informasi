<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        return response()->json([
            'period' => ['start_date' => $start, 'end_date' => $end],
            'paid_transactions' => $paidSales->count(),
            'units_sold' => $paidSales->flatMap->items->sum('quantity'),
            'revenue' => $revenue,
            'cost_of_goods_sold' => $costOfGoods,
            'operating_expenses' => $operationalExpenses,
            'net_profit' => $revenue - $costOfGoods - $operationalExpenses,
        ]);
    }
}
