<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'size_id',
        'name',
        'details',
        'image',
        'color',
        'price',
        'sku',
        'stock_quantity',
        'min_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Resolve a usable image URL whether the stored value is a remote URL
     * (e.g. from an online image link) or a locally uploaded file name.
     */
    public function getImageUrlAttribute(): string
    {
        if (blank($this->image)) {
            return 'https://via.placeholder.com/60x60?text=No+Image';
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('images/'.$this->image);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock;
    }
}
