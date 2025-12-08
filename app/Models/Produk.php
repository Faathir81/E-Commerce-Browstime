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

    public function hasSufficientStock(): bool
    {
        $recipe = $this->resep;
        if (! $recipe || $recipe->detail->isEmpty()) {
            return false;
        }

        foreach ($recipe->detail as $detail) {
            $bahan = $detail->bahan;
            // Jika data bahan tidak ada atau jumlah kebutuhannya tidak valid, anggap stok tidak cukup.
            if (! $bahan || ($detail->jumlah ?? 0) <= 0) {
                return false;
            }

            $current = $bahan->current_stok ?? 0;
            if ($current < ($detail->jumlah ?? 0)) {
                return false;
            }
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // nanti dipakai di BOM
    public function resep()
    {
        return $this->hasOne(ResepBOM::class, 'produk_id');
    }
}
