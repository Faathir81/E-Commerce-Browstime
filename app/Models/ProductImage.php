<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read string|null $url
 */
class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'url',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected $appends = ['url'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getUrlAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (preg_match('#^https?://#i', $value) || str_starts_with($value, '//')) {
            return $value;
        }

        return Storage::disk('public')->url(ltrim($value, '/'));
    }

    // helper if code calls $image->url() as method
    public function url(): ?string
    {
        return $this->getUrlAttribute($this->attributes['url'] ?? null);
    }
}
