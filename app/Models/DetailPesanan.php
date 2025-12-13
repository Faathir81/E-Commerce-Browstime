<?php

namespace App\Models;

use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPesanan extends Model
{
    protected $guarded = [];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class);
    }

    public function hasUlasan(): bool
    {
        if ($this->relationLoaded('ulasan')) {
            return (bool) $this->ulasan;
        }

        return $this->ulasan()->exists();
    }
}
