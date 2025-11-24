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
}
