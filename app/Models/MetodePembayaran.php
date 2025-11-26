<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $fillable = [
        'nama', 'kode', 'aktif'
    ];

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'metode_id');
    }
}
