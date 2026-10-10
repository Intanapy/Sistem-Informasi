<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashFlowController extends Controller
{
    public function index(): JsonResponse
    {
        $flows = CashFlow::with('employee:id,name')->latest()->paginate(100);
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $monthlyFlows = CashFlow::whereBetween('created_at', [$monthStart, $monthEnd]);

        return response()->json([
            'data' => $flows->items(),
            'current_page' => $flows->currentPage(),
            'last_page' => $flows->lastPage(),
            'total' => $flows->total(),
            'summary' => [
                'income' => (float) (clone $monthlyFlows)->where('type', 'income')->sum('amount'),
                'expense' => (float) (clone $monthlyFlows)->where('type', 'expense')->sum('amount'),
                'count' => (clone $monthlyFlows)->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'category' => ['required', Rule::in(['other_income', 'operational', 'rent', 'utilities', 'other_expense'])],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'description' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
        ]);

        if ($data['type'] === 'income' && $data['category'] !== 'other_income') {
            return response()->json([
                'message' => 'Pemasukan dari penjualan dicatat otomatis saat pembayaran dikonfirmasi.',
            ], 422);
        }

        if ($data['type'] === 'expense' && $data['category'] === 'other_income') {
            return response()->json(['message' => 'Kategori pemasukan tidak dapat digunakan untuk pengeluaran.'], 422);
        }

        $cashFlow = CashFlow::create([
            'type' => $data['type'],
            'category' => $data['category'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'user_id' => $request->user()->id,
        ]);
        $cashFlow->forceFill(['created_at' => Carbon::parse($data['date'])->startOfDay()])->save();

        return response()->json($cashFlow->load('employee:id,name'), 201);
    }
}

