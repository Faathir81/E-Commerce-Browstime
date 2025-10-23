<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'code',
        'user_id',      // nullable: guest checkout
        'buyer_name',
        'buyer_phone',
        'address',
        'status',       // pending|paid|shipped|completed|cancelled
        'shipping_fee', // integer
        'notes',        // nullable
        // 'total' — kalau ada kolom ini di DB, boleh ditambahkan
    ];

    protected $casts = [
        'shipping_fee' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function user(): BelongsTo
    {
        // nullable — aman untuk guest
        return $this->belongsTo(User::class);
    }

    public function materialStocks(): HasMany
    {
        // konsumsi stok yang terkait order ini (jika kita isi order_id di material_stocks)
        return $this->hasMany(MaterialStock::class);
    }
}
