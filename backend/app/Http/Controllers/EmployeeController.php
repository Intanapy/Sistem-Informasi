<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        $employees = User::where('role', 'employee')
            ->select(['id', 'name', 'email', 'created_at'])
            ->withCount(['sales', 'stockEntries', 'cashFlows'])
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $employees, 'total' => $employees->count()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $employee = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'employee',
        ]);

        return response()->json($employee->only(['id', 'name', 'email', 'role']), 201);
    }
}

