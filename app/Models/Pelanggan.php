<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'no_hp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alamatPengiriman()
    {
        return $this->hasMany(AlamatPengiriman::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
