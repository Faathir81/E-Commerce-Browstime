<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $fillable = [
        'kode_rajaongkir',
        'nama',
        'kota_id',
        'kode_pos',
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
}

