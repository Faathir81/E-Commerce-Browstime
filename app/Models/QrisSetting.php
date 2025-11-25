<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrisSetting extends Model
{
    protected $table = 'qris_settings';

    protected $fillable = [
        'gambar_qris',
    ];

    protected $casts = [
        'gambar_qris' => 'string',
    ];
}
