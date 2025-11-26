<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Pesanan extends Model
{
    protected $guarded = [];

    // customer (kalau pakai user langsung)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // wilayah pengiriman / supported area
    public function wilayahPengiriman(): BelongsTo
    {
        return $this->belongsTo(WilayahPengiriman::class);
        // atau SupportedArea::class kalau nama tabelnya itu
    }

    // detail item
    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    // pembayaran
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }
}
