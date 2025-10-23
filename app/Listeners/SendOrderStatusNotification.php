<?php

namespace App\Listeners;

use App\Events\PaymentVerified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification implements ShouldQueue
{
    public function handle(PaymentVerified $event): void
    {
        // Contoh paling sederhana: log.
        // Nanti bisa diganti kirim email/WA/Push sesuai kebutuhan.
        Log::info('Payment verified', [
            'order_code'   => $event->order->code,
            'payment_id'   => $event->payment->id,
            'amount'       => $event->payment->amount,
            'provider'     => $event->payment->provider,
            'method'       => $event->payment->method,
            'verified_at'  => now()->toDateTimeString(),
        ]);
    }
}
