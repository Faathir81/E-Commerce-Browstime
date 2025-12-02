<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use App\Models\Pelanggan;

class Pesanan extends Model
{
    protected $guarded = [];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_PRODUKSI = 'produksi';
    public const STATUS_DIKIRIM = 'dikirim';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_BATAL = 'batal';
    public const STATUS_PERLU_PERBAIKAN = 'perlu_perbaikan';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_PRODUKSI,
        self::STATUS_DIKIRIM,
        self::STATUS_SELESAI,
        self::STATUS_BATAL,
        self::STATUS_PERLU_PERBAIKAN,
    ];

    // customer (kalau pakai user langsung)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // wilayah pengiriman / supported area
    public function wilayahPengiriman(): BelongsTo
    {
        return $this->belongsTo(WilayahPengiriman::class);
        // atau SupportedArea::class kalau nama tabelnya itu
    }

    // detail item
    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    // pembayaran
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    /**
     * Resolve pelanggan for both login and guest orders.
     * - Login: match by user_id
     * - Guest: match by guest_email (case-insensitive)
     * - Fallback: first pelanggan without user_id
     */
    public function resolvedPelanggan(): ?Pelanggan
    {
        if ($this->user_id) {
            if ($pelanggan = Pelanggan::where('user_id', $this->user_id)->first()) {
                return $pelanggan;
            }
        }

        $email = trim(strtolower((string) $this->guest_email));
        if ($email !== '') {
            if ($pelanggan = Pelanggan::whereRaw('LOWER(email) = ?', [$email])->first()) {
                return $pelanggan;
            }
        }

        return Pelanggan::whereNull('user_id')->first();
    }

    public function getNamaPelangganAttribute(): string
    {
        return $this->resolvedPelanggan()?->nama ?? '-';
    }
}
