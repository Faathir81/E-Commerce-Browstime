<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'method',       // transfer|qris|bank_transfer|gopay|...
        'provider',     // manual|midtrans
        'status',       // pending|waiting_verification|paid|failed
        'provider_ref', // transaction_id/order_id dari gateway
        'proof_url',    // path bukti bayar (manual)
        'raw_payload',  // json dari gateway
        'notes',        // opsional alasan gagal/catatan
    ];

    protected $casts = [
        'amount'      => 'integer',
        'raw_payload' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
