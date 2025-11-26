<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $guarded = [];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function akunBank(): BelongsTo
    {
        return $this->belongsTo(AkunBank::class);
    }

    public function qrisSetting(): BelongsTo
    {
        return $this->belongsTo(QrisSetting::class);
    }
}
