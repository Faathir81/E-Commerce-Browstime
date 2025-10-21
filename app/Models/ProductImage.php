<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'url',        // simpan PATH relatif di DB (mis: products/abc.jpg)
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /** Relasi balik ke product */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Accessor: kembalikan URL publik.
     * - Jika value sudah absolut (http/https), kembalikan apa adanya.
     * - Jika relatif, buatkan URL dari disk 'public'.
     */
    public function getUrlAttribute($value): string
    {
        if (! $value) return $value;

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        return $disk->url($value); // sekarang Intelephense nggak akan ngambek
    }
    
    /** Helper untuk dapat URL publik (sama dengan accessor) */
    public function url(): string
    {
        return $this->url;
    }
}
