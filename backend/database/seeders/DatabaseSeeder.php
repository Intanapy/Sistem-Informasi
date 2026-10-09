<?php

namespace Database\Seeders;

use App\Models\CashFlow;
use App\Models\InventoryUnit;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockEntry;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::updateOrCreate(
            ['email' => 'owner@istore.demo'],
            ['name' => 'Aditya Pratama', 'password' => 'password', 'role' => 'owner'],
        );

        $employee = User::updateOrCreate(
            ['email' => 'staff@istore.demo'],
            ['name' => 'Nadia Rahma', 'password' => 'password', 'role' => 'employee'],
        );

        $basePrices = [14 => 11999000, 15 => 15499000, 16 => 16499000, 17 => 20499000, 18 => 29499000];
        $imeiSequence = 1;
        $receiptSequence = 1;

        foreach ([14, 15, 16, 17, 18] as $generation) {
            foreach ([false, true] as $proMax) {
                $name = 'iPhone '.$generation.($proMax ? ' Pro Max' : '');
                $product = Product::updateOrCreate(
                    ['name' => $name],
                    [
                        'description' => $name.' dengan pilihan penyimpanan dan warna untuk dataset demo iStore.',
                        'photo_path' => null,
                        'is_active' => true,
                    ],
                );

                foreach ([256, 512] as $capacity) {
                    foreach (['White', 'Pink'] as $color) {
                        $price = (int) (round(($basePrices[$generation] * ($proMax ? 1.18 : 1) * ($capacity === 512 ? 1.18 : 1)) / 1000) * 1000);
                        $cost = (int) (round(($price * 0.9) / 1000) * 1000);
                        $variant = ProductVariant::updateOrCreate(
                            ['product_id' => $product->id, 'capacity_gb' => $capacity, 'color' => $color],
                            ['selling_price' => $price, 'is_active' => true],
                        );

                        if ($variant->units()->exists()) {
                            continue;
                        }

                        $quantity = (($generation + $capacity + strlen($color)) % 4) + 1;
                        $entry = StockEntry::create([
                            'number' => sprintf('DEMO-STK-%04d', $receiptSequence++),
                            'user_id' => $employee->id,
                            'product_variant_id' => $variant->id,
                            'quantity' => $quantity,
                            'unit_cost' => $cost,
                            'total_cost' => $cost * $quantity,
                            'note' => 'Persediaan awal contoh untuk presentasi.',
                        ]);

                        for ($unit = 0; $unit < $quantity; $unit++) {
                            InventoryUnit::create([
                                'product_variant_id' => $variant->id,
                                'stock_entry_id' => $entry->id,
                                'imei' => sprintf('35824005%07d', $imeiSequence++),
                                'purchase_cost' => $cost,
                                'status' => 'in_stock',
                            ]);
                        }

                        CashFlow::create([
                            'user_id' => $owner->id,
                            'stock_entry_id' => $entry->id,
                            'type' => 'expense',
                            'category' => 'stock_purchase',
                            'amount' => $entry->total_cost,
                            'description' => 'Stok awal demo '.$entry->number,
                        ]);
                    }
                }
            }
        }
    }
}
