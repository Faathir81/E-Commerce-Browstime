<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    protected $table = 'detail_reseps';

    protected $fillable = [
        'resep_id',
        'bahan_id',
        'jumlah',
        'satuan_id',
    ];

    public function resep()
    {
        return $this->belongsTo(ResepBOM::class, 'resep_id');
    }

    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
