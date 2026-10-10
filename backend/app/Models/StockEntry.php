<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockEntry extends Model
{
    protected $fillable = [
        'number', 'user_id', 'product_variant_id', 'quantity', 'unit_cost', 'total_cost', 'note',
    ];

    protected function casts(): array
    {
        return ['unit_cost' => 'decimal:2', 'total_cost' => 'decimal:2'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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

