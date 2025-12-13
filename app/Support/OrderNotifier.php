<?php

namespace App\Support;

use App\Mail\OrderCreatedMail;
use App\Mail\OrderShippedMail;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotifier
{
    public static function sendOrderCreated(Pesanan $pesanan): void
    {
        $recipient = self::resolveRecipientEmail($pesanan);
        if (! $recipient) {
            return;
        }

        $customerName = self::resolveCustomerName($pesanan);
        $trackingUrl = self::trackingUrl($pesanan);

        try {
            Mail::to($recipient)->send(new OrderCreatedMail(
                pesanan: $pesanan,
                customerName: $customerName,
                trackingUrl: $trackingUrl,
            ));
        } catch (\Throwable $th) {
            Log::warning('Failed to send order confirmation email', [
                'pesanan_id' => $pesanan->id ?? null,
                'kode' => $pesanan->kode ?? null,
                'error' => $th->getMessage(),
            ]);
        }
    }

    public static function sendOrderShipped(Pesanan $pesanan): void
    {
        $recipient = self::resolveRecipientEmail($pesanan);
        if (! $recipient) {
            return;
        }

        $customerName = self::resolveCustomerName($pesanan);
        $trackingUrl = self::trackingUrl($pesanan);

        try {
            Mail::to($recipient)->send(new OrderShippedMail(
                pesanan: $pesanan,
                customerName: $customerName,
                trackingUrl: $trackingUrl,
            ));
        } catch (\Throwable $th) {
            Log::warning('Failed to send order shipped email', [
                'pesanan_id' => $pesanan->id ?? null,
                'kode' => $pesanan->kode ?? null,
                'error' => $th->getMessage(),
            ]);
        }
    }

    protected static function resolveRecipientEmail(Pesanan $pesanan): ?string
    {
        $email = $pesanan->user?->email ?: $pesanan->guest_email;
        $email = $email ? trim($email) : null;

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $email;
    }

    protected static function resolveCustomerName(Pesanan $pesanan): string
    {
        return $pesanan->user?->name
            ?? $pesanan->nama_pelanggan
            ?? 'Pelanggan';
    }

    protected static function trackingUrl(Pesanan $pesanan): string
    {
        return route('order.success', ['kode' => $pesanan->kode]);
    }
}
