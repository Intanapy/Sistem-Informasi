<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_variant_id', 'quantity', 'unit_price', 'average_unit_cost',
    ];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2', 'average_unit_cost' => 'decimal:2'];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(InventoryUnit::class);
    }
}
