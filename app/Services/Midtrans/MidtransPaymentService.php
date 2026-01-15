<?php

namespace App\Services\Midtrans;

use App\Models\Pesanan;
use Midtrans\Snap;

class MidtransPaymentService
{
    public function __construct(private MidtransConfigService $configService)
    {
    }

    /**
     * Create a Midtrans Snap transaction for a given order.
     *
     * @return array{payload: array<string, mixed>, response: array<string, mixed>, gross_amount: int}
     */
    public function createTransaction(Pesanan $pesanan, ?string $orderId = null): array
    {
        $pesanan->loadMissing(['detailPesanans.produk']);

        $itemDetails = $this->buildItemDetails($pesanan);
        $grossAmount = $this->calculateGrossAmount($itemDetails);
        $resolvedOrderId = $orderId ?: $pesanan->kode;

        $callbacks = [
            'finish' => route('payments.midtrans.finish', ['kode' => $pesanan->kode]),
            'unfinish' => route('payments.midtrans.finish', ['kode' => $pesanan->kode]),
            'error' => route('payments.midtrans.finish', ['kode' => $pesanan->kode]),
        ];

        $payload = [
            'transaction_details' => [
                'order_id' => $resolvedOrderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => $this->buildCustomerDetails($pesanan),
            'callbacks' => $callbacks,
        ];

        $this->configService->configure();
        $response = Snap::createTransaction($payload);
        $responseData = is_object($response) ? (array) $response : (array) $response;

        return [
            'payload' => $payload,
            'response' => $responseData,
            'gross_amount' => $grossAmount,
        ];
    }

    private function buildItemDetails(Pesanan $pesanan): array
    {
        $items = [];

        foreach ($pesanan->detailPesanans as $detail) {
            $items[] = [
                'id' => (string) $detail->produk_id,
                // price diambil dari harga final saat checkout, bukan dari master produk
                'price' => (int) round($detail->harga ?? 0),
                'quantity' => (int) $detail->qty,
                'name' => $detail->produk->nama ?? 'Produk',
            ];
        }

        $items[] = [
            'id' => 'ONGKIR',
            'price' => (int) round($pesanan->ongkir ?? 0),
            'quantity' => 1,
            'name' => 'Ongkir',
        ];

        return $items;
    }

    private function calculateGrossAmount(array $itemDetails): int
    {
        return array_reduce(
            $itemDetails,
            fn (int $carry, array $item) => $carry + ($item['price'] * $item['quantity']),
            0
        );
    }

    private function buildCustomerDetails(Pesanan $pesanan): array
    {
        $pelanggan = $pesanan->resolvedPelanggan();

        return [
            'first_name' => $pelanggan?->nama ?? 'Customer',
            'email' => $pelanggan?->email
                ?? $pesanan->guest_email
                ?? optional($pesanan->user)->email
                ?? 'customer@example.com',
            'phone' => $pelanggan?->no_hp,
        ];
    }
}
