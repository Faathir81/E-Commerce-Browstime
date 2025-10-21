<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'price' => 'integer',
        'estimated_days' => 'integer',
        'is_best_seller' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}