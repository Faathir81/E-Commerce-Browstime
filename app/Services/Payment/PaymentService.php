<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function createManual(Order $order, int $amount, string $method = 'transfer'): Payment
    {
        if (! in_array($method, ['transfer', 'qris'])) {
            throw ValidationException::withMessages(['method' => 'Unsupported manual method.']);
        }

        return DB::transaction(function () use ($order, $amount, $method) {
            return Payment::create([
                'order_id'     => $order->id,
                'amount'       => $amount,
                'method'       => $method,               // transfer|qris
                'provider'     => 'manual',
                'status'       => 'pending',             // pending|waiting_verification|paid|failed
                'provider_ref' => null,
                'raw_payload'  => null,
            ]);
        });
    }

    public function attachProof(Payment $payment, UploadedFile $file): Payment
    {
        if ($payment->provider !== 'manual') {
            throw ValidationException::withMessages(['payment' => 'Proof upload only for manual payments.']);
        }

        $path = $file->store('payments', 'public');

        $payment->update([
            'proof_url' => $path,
            'status'    => 'waiting_verification',
        ]);

        return $payment;
    }

    public function markPaid(Payment $payment): void
    {
        if ($payment->status === 'paid') {
            return;
        }

        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'paid']);

            // Update order status -> paid (jangan kurangi stok di sini; pakai Event)
            $order = $payment->order()->lockForUpdate()->first();
            if ($order && $order->status !== 'paid') {
                $order->update(['status' => 'paid']);
            }

            // setelah $order->update(['status' => 'paid']);
            if (class_exists(\App\Events\PaymentVerified::class)) {
                event(new \App\Events\PaymentVerified($order, $payment));
            }

            // Dispatch event untuk kurangi stok bahan baku (jika event tersedia)
            if (class_exists(\App\Events\OrderPaid::class)) {
                event(new \App\Events\OrderPaid($order));
            }
        });
    }

    public function markFailed(Payment $payment, ?string $reason = null): void
    {
        $payment->update([
            'status' => 'failed',
            'notes'  => $reason,
        ]);
    }
}
