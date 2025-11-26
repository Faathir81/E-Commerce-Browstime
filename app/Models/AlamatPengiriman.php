<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatPengiriman extends Model
{
    protected $table = 'alamat_pengirimans';

    protected $fillable = [
        'pelanggan_id',
        'nama_penerima',
        'no_hp',
        'alamat_lengkap',
        'kode_pos',
        'wilayah_pengiriman_id',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function wilayah()
    {
        return $this->belongsTo(WilayahPengiriman::class, 'wilayah_pengiriman_id');
    }
}
