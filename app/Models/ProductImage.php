<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor URL publik (storage link).
     * Dipakai di controller/service: $image->url
     */
    protected function url(): Attribute
    {
        return Attribute::get(function () {
            $path = $this->path;
            if (! $path) return null;
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            return asset('storage/' . ltrim($path, '/'));
        });
    }
}
