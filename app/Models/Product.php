<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_label',
        'description',
        'price',
        'estimated_days',
        'is_best_seller',
        'is_active',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'estimated_days' => 'integer',
        'is_best_seller' => 'boolean',
        'is_active'      => 'boolean',
    ];

    /** Relasi gambar, default urut ASC by sort_order */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')
            ->orderBy('sort_order', 'asc');
    }

    /** Relasi kategori (pivot: product_categories) */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    /** Scope produk aktif (opsional) */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
