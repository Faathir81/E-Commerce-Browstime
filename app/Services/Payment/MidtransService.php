<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MidtransService
{
    protected string $serverKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serverKey    = (string) config('services.midtrans.server_key');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
        $this->baseUrl      = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    /**
     * Buat transaksi Core API (contoh: QRIS / VA).
     * $channel contoh: 'qris', 'bank_transfer' (BCA), 'gopay' (opsional).
     * Return minimal data untuk FE: transaction_id, redirect_url/deeplink/qr string (jika ada).
     */
    public function createCharge(Order $order, int $amount, string $channel = 'qris'): array
    {
        $payload = [
            'transaction_details' => [
                'order_id'      => $order->code,
                'gross_amount'  => $amount,
            ],
            'customer_details' => [
                'first_name' => $order->buyer_name,
                'phone'      => $order->buyer_phone,
                'billing_address' => [
                    'address' => $order->address,
                ],
            ],
        ];

        // Channel sederhana
        if ($channel === 'qris') {
            $payload['payment_type'] = 'qris';
            $payload['qris'] = [
                'acquirer' => 'gopay', // default aman
            ];
        } elseif ($channel === 'bank_transfer') {
            $payload['payment_type'] = 'bank_transfer';
            $payload['bank_transfer'] = ['bank' => 'bca']; // default contoh
        } elseif ($channel === 'gopay') {
            $payload['payment_type'] = 'gopay';
        } else {
            throw ValidationException::withMessages(['channel' => 'Unsupported channel.']);
        }

        $resp = Http::withBasicAuth($this->serverKey, '')
            ->acceptJson()
            ->post("{$this->baseUrl}/charge", $payload);

        if (! $resp->successful()) {
            throw ValidationException::withMessages(['midtrans' => 'Failed to create transaction.']);
        }

        $data = $resp->json();

        // Simpan/refresh payment record
        DB::transaction(function () use ($order, $amount, $data, $channel) {
            Payment::updateOrCreate(
                [
                    'order_id'     => $order->id,
                    'provider'     => 'midtrans',
                    'provider_ref' => $data['transaction_id'] ?? $data['order_id'] ?? $order->code,
                ],
                [
                    'amount'      => $amount,
                    'method'      => $channel,
                    'status'      => 'pending',
                    'raw_payload' => $data,
                ]
            );
        });

        return [
            'order_id'        => $order->code,
            'transaction_id'  => $data['transaction_id'] ?? null,
            'status_code'     => $data['status_code'] ?? null,
            'payment_type'    => $data['payment_type'] ?? null,
            'redirect_url'    => $data['redirect_url'] ?? null,
            'actions'         => $data['actions'] ?? null,    // gopay/qr
            'qr_string'       => $data['qr_string'] ?? null,  // qris
            'va_numbers'      => $data['va_numbers'] ?? null, // bank_transfer
        ];
    }

    /**
     * Verifikasi signature key dari notifikasi Midtrans.
     */
    public function verifySignature(array $payload): bool
    {
        $orderId     = (string) ($payload['order_id'] ?? '');
        $statusCode  = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature   = (string) ($payload['signature_key'] ?? '');

        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($computed, $signature);
    }

    /**
     * Proses notifikasi Midtrans → update Payment & Order.
     */
    public function handleNotification(array $payload): void
    {
        if (! $this->verifySignature($payload)) {
            throw ValidationException::withMessages(['signature' => 'Invalid signature.']);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $orderCode         = $payload['order_id'] ?? null;

        if (! $orderCode) {
            throw ValidationException::withMessages(['order' => 'order_id missing.']);
        }

        /** @var Order|null $order */
        $order = Order::where('code', $orderCode)->first();
        if (! $order) {
            throw ValidationException::withMessages(['order' => 'Order not found.']);
        }

        /** @var Payment|null $payment */
        $payment = Payment::where('order_id', $order->id)
            ->where('provider', 'midtrans')
            ->latest('id')
            ->first();

        if (! $payment) {
            // create a new payment record for safety
            $payment = Payment::create([
                'order_id'     => $order->id,
                'amount'       => (int) ($payload['gross_amount'] ?? $order->total),
                'method'       => $payload['payment_type'] ?? 'midtrans',
                'provider'     => 'midtrans',
                'provider_ref' => $payload['transaction_id'] ?? $order->code,
                'status'       => 'pending',
                'raw_payload'  => $payload,
            ]);
        } else {
            $payment->update(['raw_payload' => $payload]);
        }

        // Mapping status
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $payment->update(['status' => 'waiting_verification']);
            } elseif ($fraudStatus === 'accept') {
                app(PaymentService::class)->markPaid($payment);
            }
        } elseif ($transactionStatus === 'settlement') {
            app(PaymentService::class)->markPaid($payment);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
            app(PaymentService::class)->markFailed($payment, $transactionStatus);
        } elseif ($transactionStatus === 'pending') {
            $payment->update(['status' => 'pending']);
        }
    }
}
