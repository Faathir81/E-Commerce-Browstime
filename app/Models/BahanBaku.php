<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_bakus';

    protected $fillable = [
        'nama',
        'satuan_id',
        'stok_awal',
        'stok_minimum',
        'keterangan',
    ];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function mutasi()
    {
        return $this->hasMany(MutasiStok::class, 'bahan_id');
    }

    public function getStokAttribute(): float
    {
        // stok awal dari database
        $awal = (float) $this->stok_awal;

        // hitung semua mutasi (stok_masuk +, pemakaian produksi -, penyesuaian dsb.)
        $mutasiMasuk = $this->mutasi()
            ->whereIn('jenis_mutasi', ['stok_masuk'])
            ->sum('qty');

        $mutasiKeluar = $this->mutasi()
            ->whereIn('jenis_mutasi', ['pemakaian_produksi', 'stok_keluar', 'penyesuaian_minus'])
            ->sum('qty');

        return $awal + $mutasiMasuk - $mutasiKeluar;
    }
}
