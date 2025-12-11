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
            'pembayaran.akunBank',
            'detailPesanans.produk',
            'wilayahPengiriman.provinsi',
            'wilayahPengiriman.kota',
            'wilayahPengiriman.kecamatan',
        ])->where('kode', $kode)->first();

        if (! $pesanan) {
            abort(404);
        }

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

        $etaText = $pesanan?->eta
            ? Carbon::parse($pesanan->eta)->timezone('Asia/Jakarta')->format('d M Y')
            : ($pesanan
                ? $pesanan->created_at->copy()->addDays(2)->timezone('Asia/Jakarta')->format('d M Y')
                : 'ETA unavailable');

        $paymentInfo = [
            'method' => $pembayaran?->metode ?? '-',
            'method_label' => \App\Support\StatusStyle::metodePembayaran($pembayaran?->metode)['label'] ?? '-',
            'qr_image_url' => $pembayaran?->qrisSetting?->gambar_qris
                ? $publicDisk->url($pembayaran->qrisSetting->gambar_qris)
                : null,
            'proof_image_url' => $pembayaran?->bukti_bayar
                ? $publicDisk->url($pembayaran->bukti_bayar)
                : null,
            'badge' => $paymentBadge,
            'status_label' => \App\Support\StatusStyle::pembayaran($pembayaran?->status)['label'] ?? '-',
            'is_transfer' => ($pembayaran?->metode ?? null) === 'transfer',
            'account' => $pembayaran?->akunBank ? [
                'bank' => $pembayaran->akunBank->nama_bank ?? 'Bank',
                'owner' => $pembayaran->akunBank->nama_pemilik ?? null,
                'number' => $pembayaran->akunBank->nomor_rekening ?? null,
            ] : null,
        ];

        $deliveryInfo = [
            'eta_text' => $etaText,
            'tracking_number' => $pesanan?->no_resi ?: null,
            'tracking_url' => $pesanan?->no_resi
                ? 'https://cekresi.com/?no=' . urlencode($pesanan->no_resi)
                : null,
            'is_shipped' => $pesanan?->status === \App\Models\Pesanan::STATUS_DIKIRIM
                || $pesanan?->status === \App\Models\Pesanan::STATUS_SELESAI,
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
