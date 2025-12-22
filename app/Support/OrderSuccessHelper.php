<?php

namespace App\Support;

use App\Models\AlamatPengiriman;
use App\Models\Pelanggan;
use App\Models\Pesanan;

class OrderSuccessHelper
{
    public static function formatFullAddress(
        ?Pesanan $pesanan,
        ?Pelanggan $pelanggan,
        ?AlamatPengiriman $alamatPengiriman
    ): string {
        $wilayah = $alamatPengiriman?->wilayah ?? $pesanan?->wilayahPengiriman;
        $alamatLengkap = $pesanan?->alamat_lengkap ?: $alamatPengiriman?->alamat_lengkap;

        return trim(collect([
            $alamatLengkap,
            $pesanan?->kecamatan->nama ?? null,
            $wilayah?->kota->nama ?? null,
            $wilayah?->provinsi->nama ?? null,
            $alamatPengiriman?->kode_pos ? 'Postal Code ' . $alamatPengiriman->kode_pos : null,
        ])->filter()->implode(', '));
    }

    public static function mapPaymentBadge(?string $paymentStatus): array
    {
        $status = $paymentStatus ?: 'pending';

        $badges = [
            'pending' => ['label' => 'Pls wait us to verificate your payment :D', 'bg' => 'bg-[#fff3d4]', 'text' => 'text-[#a36a0f]'],
            'menunggu_verifikasi' => ['label' => 'Waiting Verification', 'bg' => 'bg-[#fff3d4]', 'text' => 'text-[#a36a0f]'],
            'valid' => ['label' => 'Confirmed', 'bg' => 'bg-[#e8f7e5]', 'text' => 'text-[#2f7a3d]'],
            'invalid' => ['label' => 'Declined', 'bg' => 'bg-[#fdecea]', 'text' => 'text-[#b3261e]'],
        ];

        return $badges[$status] ?? $badges['pending'];
    }

    public static function mapOrderSteps(?string $orderStatus): array
    {
        $statusMap = [
            Pesanan::STATUS_PAID => 'confirmed',
            Pesanan::STATUS_PRODUKSI => 'baking',
            Pesanan::STATUS_DIKIRIM => 'delivery',
            Pesanan::STATUS_SELESAI => 'delivered',
        ];

        $statusOrder = ['confirmed', 'baking', 'delivery', 'delivered'];
        $currentStep = $statusMap[$orderStatus ?? ''] ?? null;
        $currentIndex = $currentStep ? array_search($currentStep, $statusOrder, true) : false;

        $steps = [
            ['key' => 'confirmed', 'title' => 'Order Confirmed', 'desc' => 'Your order has been received'],
            ['key' => 'baking', 'title' => 'Baking in Progress', 'desc' => "We're preparing your order"],
            ['key' => 'delivery', 'title' => 'Out for Delivery', 'desc' => 'Your order is on the way'],
            ['key' => 'delivered', 'title' => 'Delivered', 'desc' => 'Package arrived'],
        ];

        return collect($steps)->map(function (array $step) use ($statusOrder, $currentIndex) {
            $stepIndex = array_search($step['key'], $statusOrder, true);
            $isActive = $currentIndex !== false && $stepIndex !== false && $stepIndex <= $currentIndex;

            return array_merge($step, [
                'is_active' => $isActive,
                'dot_classes' => $isActive ? 'border-[#7a4b24] bg-[#7a4b24]' : 'border-[#d9c7b7] bg-[#f6eee4]',
                'text_classes' => $isActive ? 'text-[#3b241a]' : 'text-[#6f4c3b]',
            ]);
        })->values()->all();
    }
}
