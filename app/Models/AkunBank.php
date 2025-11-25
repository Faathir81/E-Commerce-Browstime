<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunBank extends Model
{
    protected $table = 'akun_banks';

    protected $fillable = [
        'nama_bank',
        'nama_pemilik',
        'nomor_rekening',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];
}