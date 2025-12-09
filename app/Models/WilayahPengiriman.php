<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahPengiriman extends Model
{
    protected $table = 'wilayah_pengiriman';

    protected $fillable = [
        'nama',
        'provinsi_id',
        'kota_id',
        'kecamatan_id',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
