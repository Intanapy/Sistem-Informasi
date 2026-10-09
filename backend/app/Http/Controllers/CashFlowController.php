<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashFlowController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            CashFlow::with('employee:id,name')->latest()->paginate(20),
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'category' => ['required', Rule::in(['other_income', 'operational', 'rent', 'utilities', 'other_expense'])],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'description' => ['required', 'string', 'max:255'],
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
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($cashFlow->load('employee:id,name'), 201);
    }
}
