<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialStock extends Model
{
    protected $fillable = [
        'material_id',
        'qty',       // catat positif untuk masuk, negatif untuk konsumsi
        'type',      // opsional: in|out|adjustment
        'note',      // opsional
        'order_id',  // opsional: referensi order (konsumsi)
    ];

    protected $casts = [
        'qty' => 'float',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
