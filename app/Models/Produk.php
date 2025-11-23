<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'nama',
        'slug',
        'kategori_id',
        'harga',
        'deskripsi',
        'waktu_produksi',
        'gambar',
        'is_active',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // nanti dipakai di BOM
    public function resep()
    {
        return $this->hasOne(ResepBOM::class, 'produk_id');
    }
}
