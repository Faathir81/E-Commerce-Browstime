<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MidtransSetting extends Model
{
    protected $table = 'midtrans_settings';

    protected $fillable = [
        'server_key',
        'client_key',
        'is_production',
    ];

    protected $casts = [
        'is_production' => 'boolean',
    ];
}
