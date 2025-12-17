<?php

namespace App\Services\Midtrans;

use App\Models\MidtransSetting;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class MidtransWebhookService
{
    /**
     * Handle Midtrans webhook payload with signature validation and status updates.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function handle(array $payload): array
    {
        $serverKey = $this->getServerKey();

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = strtolower((string) ($payload['transaction_status'] ?? ''));
        $transactionId = (string) ($payload['transaction_id'] ?? '');

        if (! $this->isValidSignature($orderId, $statusCode, $grossAmount, $signatureKey, $serverKey)) {
            Log::warning('Midtrans webhook rejected: invalid signature', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payload' => $payload,
            ]);

            throw new InvalidArgumentException('Invalid signature.');
        }

        /** @var Pesanan $pesanan */
        $pesanan = Pesanan::with('pembayaran')->where('kode', $orderId)->first();

        if (! $pesanan) {
            Log::warning('Midtrans webhook: order not found', [
                'order_id' => $orderId,
                'payload' => $payload,
            ]);

            throw new ModelNotFoundException('Order not found.');
        }

        $statusMapping = $this->mapTransactionStatus($transactionStatus);
        $shouldUpdate = $statusMapping['pesanan_status']
            || $statusMapping['pembayaran_status']
            || $transactionId;

        if (! $shouldUpdate) {
            return [
                'order_id' => $orderId,
                'status' => 'ignored',
                'transaction_status' => $transactionStatus,
            ];
        }

        $result = DB::transaction(function () use ($pesanan, $statusMapping, $transactionId) {
            $pesananUpdate = [];
            if ($statusMapping['pesanan_status'] && $pesanan->status !== $statusMapping['pesanan_status']) {
                $pesananUpdate['status'] = $statusMapping['pesanan_status'];
            }

            if ($pesananUpdate) {
                $pesanan->update($pesananUpdate);
            }

            /** @var Pembayaran|null $pembayaran */
            $pembayaran = $pesanan->pembayaran;
            if ($pembayaran) {
                $pembayaranUpdate = [];

                if ($statusMapping['pembayaran_status'] && $pembayaran->status !== $statusMapping['pembayaran_status']) {
                    $pembayaranUpdate['status'] = $statusMapping['pembayaran_status'];
                }

                if ($transactionId && $pembayaran->midtrans_transaction_id !== $transactionId) {
                    $pembayaranUpdate['midtrans_transaction_id'] = $transactionId;
                }

                if ($pembayaranUpdate) {
                    $pembayaran->update($pembayaranUpdate);
                }
            }

            return [
                'pesanan_id' => $pesanan->id,
                'pesanan_status' => $pesananUpdate['status'] ?? $pesanan->status,
                'pembayaran_status' => $pembayaran ? $pembayaran->status : null,
                'midtrans_transaction_id' => $pembayaran ? $pembayaran->midtrans_transaction_id : ($transactionId ?: null),
            ];
        });

        return $result;
    }

    private function getServerKey(): string
    {
        $setting = MidtransSetting::first();

        if (! $setting || ! $setting->server_key) {
            throw new RuntimeException('Midtrans server key not configured.');
        }

        return $setting->server_key;
    }

    private function isValidSignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey, string $serverKey): bool
    {
        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            return false;
        }

        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($computed, $signatureKey);
    }

    /**
     * Map Midtrans transaction_status to local statuses.
     *
     * @return array{pesanan_status: string|null, pembayaran_status: string|null}
     */
    private function mapTransactionStatus(string $transactionStatus): array
    {
        return match ($transactionStatus) {
            'capture', 'settlement' => [
                'pesanan_status' => Pesanan::STATUS_PAID,
                'pembayaran_status' => 'valid',
            ],
            'pending' => [
                'pesanan_status' => Pesanan::STATUS_PENDING,
                'pembayaran_status' => 'pending',
            ],
            'deny' => [
                'pesanan_status' => Pesanan::STATUS_PERLU_PERBAIKAN,
                'pembayaran_status' => 'invalid',
            ],
            'expire', 'cancel' => [
                'pesanan_status' => Pesanan::STATUS_BATAL,
                'pembayaran_status' => 'invalid',
            ],
            default => [
                'pesanan_status' => null,
                'pembayaran_status' => null,
            ],
        };
    }
}
