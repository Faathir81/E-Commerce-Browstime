<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function coverImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_cover', 1);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(ProductRecipe::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}