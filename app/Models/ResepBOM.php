<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResepBOM extends Model
{
    use SoftDeletes;

    protected $table = 'resep_boms';

    protected $fillable = [
        'produk_id',
        'deskripsi',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailResep::class, 'resep_id');
    }
}
