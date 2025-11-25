<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportedArea extends Model
{
    protected $fillable = [
        'name',
        'province_id',
        'city_id',
        'subdistrict_id',
        'active',
    ];
}
