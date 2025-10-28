<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'gateway',           // manual | midtrans
        'gateway_order_id',  // id order dari gateway (opsional)
        'transaction_id',    // id transaksi dari gateway (opsional)
        'method',            // transfer | qris | cash
        'amount',
        'currency',          // default 'IDR'
        'status',            // pending | verified | failed
        'channel',           // bebas (bank/ewallet)
        'va_number',         // opsional
        'qr_string',         // opsional
        'payload',           // json dari gateway
        'proof_url',         // bukti bayar manual
        'paid_at',
    ];

    protected $casts = [
        'amount'  => 'integer',
        'payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
