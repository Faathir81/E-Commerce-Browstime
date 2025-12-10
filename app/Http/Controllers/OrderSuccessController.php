<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Support\OrderSuccessHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderSuccessController extends Controller
{
    public function __invoke(string $kode)
    {
        $pesanan = Pesanan::with([
            'pembayaran.qrisSetting',
            'detailPesanans.produk',
            'wilayahPengiriman.provinsi',
            'wilayahPengiriman.kota',
            'wilayahPengiriman.kecamatan',
        ])->where('kode', $kode)->first();

        $pembayaran = $pesanan?->pembayaran;
        $pelanggan = $pesanan?->resolvedPelanggan();
        $alamatPengiriman = $pelanggan?->alamatPengiriman()->latest()->first();

        $fullAddress = OrderSuccessHelper::formatFullAddress($pesanan, $pelanggan, $alamatPengiriman);
        $paymentBadge = OrderSuccessHelper::mapPaymentBadge($pembayaran?->status);
        $deliverySteps = OrderSuccessHelper::mapOrderSteps($pesanan?->status);

        $firstItem = $pesanan?->detailPesanans->first();
        $orderItems = ($pesanan?->detailPesanans ?? collect())->map(function ($detail) {
            return [
                'product_name' => $detail->produk->nama ?? 'Produk',
                'qty' => $detail->qty,
                'subtotal_display' => number_format($detail->subtotal ?? 0, 0, ',', '.'),
            ];
        });

        $authUser = Auth::user();
        $customerName = $authUser?->name
            ?? $pelanggan?->nama
            ?? $pesanan?->nama_penerima
            ?? 'customer';
        $confirmationEmail = $authUser?->email ?? ($pesanan?->guest_email ?? '-');
        $orderCode = $pesanan?->kode ?? $kode ?? '-';

        $orderSummary = [
            'items' => $orderItems,
            'items_count' => $orderItems->count(),
            'subtotal_display' => number_format($pesanan?->subtotal ?? 0, 0, ',', '.'),
            'shipping_display' => number_format($pesanan?->ongkir ?? 0, 0, ',', '.'),
            'total_display' => number_format($pesanan?->total ?? 0, 0, ',', '.'),
        ];

        /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
        $publicDisk = Storage::disk('public');

        $paymentInfo = [
            'method' => $pembayaran?->metode ?? '-',
            'qr_image_url' => $pembayaran?->qrisSetting?->gambar_qris
                ? $publicDisk->url($pembayaran->qrisSetting->gambar_qris)
                : null,
            'badge' => $paymentBadge,
        ];

        $deliveryInfo = [
            'eta_text' => $pesanan?->eta
                ? Carbon::parse($pesanan->eta)->format('d M Y, H:i')
                : '1-2 days',
        ];

        return view('order-success', [
            'pesanan' => $pesanan,
            'pembayaran' => $pembayaran,
            'pelanggan' => $pelanggan,
            'alamatPengiriman' => $alamatPengiriman,
            'fullAddress' => $fullAddress,
            'paymentInfo' => $paymentInfo,
            'deliverySteps' => $deliverySteps,
            'firstItem' => $firstItem,
            'customerName' => $customerName,
            'orderSummary' => $orderSummary,
            'deliveryInfo' => $deliveryInfo,
            'confirmationEmail' => $confirmationEmail,
            'orderCode' => $orderCode,
        ]);
    }
}
