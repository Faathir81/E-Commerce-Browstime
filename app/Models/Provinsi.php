<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';

    protected $fillable = [
        'kode_rajaongkir',
        'nama',
    ];

    public function kota()
    {
        return $this->hasMany(Kota::class);
    }
}
