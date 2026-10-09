<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'capacity_gb', 'color', 'selling_price', 'is_active'];

    protected function casts(): array
    {
        return ['selling_price' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(InventoryUnit::class);
    }

    public function availableUnits(): HasMany
    {
        return $this->units()->where('status', 'in_stock');
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->availableUnits()->count();
    }
}
