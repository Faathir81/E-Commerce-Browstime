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
        if (! $recipe) {
            return true;
        }

        foreach ($recipe->detail as $detail) {
            $bahan = $detail->bahan;
            // Jika data bahan tidak ada, anggap tidak cukup stok untuk menghindari oversell.
            if (! $bahan) {
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
