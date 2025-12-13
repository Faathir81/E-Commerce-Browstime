<?php

namespace App\Support;

use App\Models\DetailPesanan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\User;

class ReviewGuard
{
    public static function canReviewDetail(DetailPesanan $detail, ?User $user, ?string $guestEmailInput = null): bool
    {
        $pesanan = $detail->pesanan;
        if (! $pesanan || $pesanan->status !== Pesanan::STATUS_SELESAI) {
            return false;
        }

        if ($detail->hasUlasan()) {
            return false;
        }

        if ($pesanan->user_id) {
            return $user && $user->id === $pesanan->user_id;
        }

        $orderGuestEmail = $pesanan->guest_email ? strtolower(trim($pesanan->guest_email)) : null;
        $inputEmail = $guestEmailInput ? strtolower(trim($guestEmailInput)) : null;

        if ($orderGuestEmail) {
            return $inputEmail !== null && $inputEmail !== '' && $inputEmail === $orderGuestEmail;
        }

        return false;
    }

    public static function resolveAuthorPelanggan(DetailPesanan $detail): ?Pelanggan
    {
        $pesanan = $detail->pesanan;
        if (! $pesanan) {
            return null;
        }

        if ($pesanan->user_id) {
            return Pelanggan::where('user_id', $pesanan->user_id)->first();
        }

        $orderGuestEmail = $pesanan->guest_email ? strtolower(trim($pesanan->guest_email)) : null;
        if ($orderGuestEmail) {
            return Pelanggan::whereRaw('LOWER(email) = ?', [$orderGuestEmail])->first();
        }

        return null;
    }
}
