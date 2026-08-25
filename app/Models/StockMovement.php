<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reason',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'stock_in' => 'Stock In',
            'stock_out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'stock_in' => 'success',
            'stock_out' => 'danger',
            'adjustment' => 'warning',
            default => 'secondary',
        };
    }
}