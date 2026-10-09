<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $variants = ProductVariant::with('product:id,name,description,photo_path')
            ->withCount(['units as stock' => fn ($query) => $query->where('status', 'in_stock')])
            ->where('is_active', true)
            ->orderBy('product_id')
            ->orderBy('capacity_gb')
            ->get();

        return response()->json($variants);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'photo_path' => ['nullable', 'string', 'max:255'],
            'capacity_gb' => ['required', 'integer', Rule::in([256, 512])],
            'color' => ['required', Rule::in(['White', 'Pink'])],
            'selling_price' => ['required', 'numeric', 'min:1', 'max:999999999999'],
        ]);

        $variant = DB::transaction(function () use ($data): ProductVariant {
            $product = Product::firstOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'] ?? null,
                    'photo_path' => $data['photo_path'] ?? null,
                ],
            );

            return ProductVariant::create([
                'product_id' => $product->id,
                'capacity_gb' => $data['capacity_gb'],
                'color' => $data['color'],
                'selling_price' => $data['selling_price'],
            ]);
        });

        return response()->json($variant->load('product'), 201);
    }

    public function update(Request $request, ProductVariant $variant): JsonResponse
    {
        $data = $request->validate([
            'selling_price' => ['sometimes', 'numeric', 'min:1', 'max:999999999999'],
            'is_active' => ['sometimes', 'boolean'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'photo_path' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($variant, $data): void {
            $variant->update(collect($data)->only(['selling_price', 'is_active'])->all());
            $variant->product->update(collect($data)->only(['description', 'photo_path'])->all());
        });

        return response()->json($variant->fresh()->load('product'));
    }
}
