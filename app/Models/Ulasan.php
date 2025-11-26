<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'detail_pesanan_id',
        'rating',
        'komentar',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class);
    }
}
