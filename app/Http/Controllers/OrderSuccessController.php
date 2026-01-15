<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Support\OrderSuccessHelper;
use App\Support\ReviewGuard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class OrderSuccessController extends Controller
{
    public function __invoke(string $kode)
    {
        $pesanan = Pesanan::with([
            'pembayaran.qrisSetting',
            'pembayaran.akunBank',
            'detailPesanans.produk',
            'detailPesanans.ulasan',
            'wilayahPengiriman.provinsi',
            'wilayahPengiriman.kota',
            'kecamatan',
        ])->where('kode', $kode)->first();

        if (! $pesanan) {
            abort(404);
        }

        $authUser = Auth::user();
        $pembayaran = $pesanan?->pembayaran;
        $pelanggan = $pesanan?->resolvedPelanggan();
        $alamatPengiriman = $pelanggan?->alamatPengiriman()->latest()->first();

        $fullAddress = OrderSuccessHelper::formatFullAddress($pesanan, $pelanggan, $alamatPengiriman);
        $paymentBadge = OrderSuccessHelper::mapPaymentBadge($pembayaran?->status);
        $deliverySteps = OrderSuccessHelper::mapOrderSteps($pesanan?->status);

        $guestEmailForReview = request()->input('guest_email');
        $reviewPermissions = ($pesanan?->detailPesanans ?? collect())
            ->mapWithKeys(function ($detail) use ($authUser, $guestEmailForReview) {
                return [
                    $detail->id => [
                        'can_review' => ReviewGuard::canReviewDetail($detail, $authUser, $guestEmailForReview),
                        'has_review' => $detail->hasUlasan(),
                    ],
                ];
            })
            ->toArray();

        $firstItem = $pesanan?->detailPesanans->first();
        $orderItems = ($pesanan?->detailPesanans ?? collect())->map(function ($detail) use ($reviewPermissions) {
            $permissions = $reviewPermissions[$detail->id] ?? ['can_review' => false, 'has_review' => false];

            return [
                'detail_id' => $detail->id,
                'product_name' => $detail->produk->nama ?? 'Produk',
                'qty' => $detail->qty,
                'subtotal_display' => number_format($detail->subtotal ?? 0, 0, ',', '.'),
                'can_review' => $permissions['can_review'],
                'has_review' => $permissions['has_review'],
            ];
        });

        $isOwnerViewing = $authUser && $pesanan?->user_id && $authUser->id === $pesanan->user_id;

        $customerName = $pelanggan?->nama
            ?? $pesanan?->nama_penerima
            ?? ($isOwnerViewing ? ($authUser?->name ?? null) : null)
            ?? 'customer';

        $confirmationEmail = $isOwnerViewing
            ? ($authUser?->email ?? ($pesanan?->guest_email ?? '-'))
            : ($pesanan?->guest_email ?? ($authUser?->email ?? '-'));
        $orderCode = $pesanan?->kode ?? $kode ?? '-';

        $canConfirmCompletion = $pesanan?->status === \App\Models\Pesanan::STATUS_DIKIRIM;
        $isCompleted = $pesanan?->status === \App\Models\Pesanan::STATUS_SELESAI;

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
        $canRetryPayment = ($pesanan?->status === \App\Models\Pesanan::STATUS_PENDING)
            && (($pembayaran?->status ?? null) === 'pending')
            && (($pembayaran?->metode ?? null) === 'midtrans');

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
            'canRetryPayment' => $canRetryPayment,
            'confirmationEmail' => $confirmationEmail,
            'orderCode' => $orderCode,
            'canConfirmCompletion' => $canConfirmCompletion,
            'isCompleted' => $isCompleted,
            'reviewPermissions' => $reviewPermissions,
            'guestEmailForReview' => $guestEmailForReview,
        ]);
    }

    public function verifyGuestEmail(Request $request, string $kode)
    {
        $request->validate([
            'guest_email' => ['required', 'email'],
        ]);

        $pesanan = Pesanan::where('kode', $kode)->first();

        if (! $pesanan) {
            return response()->json([
                'valid' => false,
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if ($pesanan->user_id) {
            return response()->json([
                'valid' => false,
                'message' => 'Pesanan ini terhubung ke akun.',
            ], 422);
        }

        $inputEmail = strtolower(trim($request->input('guest_email')));
        $orderEmail = $pesanan->guest_email ? strtolower(trim($pesanan->guest_email)) : null;

        if (! $orderEmail || $inputEmail !== $orderEmail) {
            return response()->json([
                'valid' => false,
                'message' => 'Harap masukkan email yang benar.',
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'email' => $inputEmail,
            'message' => 'Email terverifikasi. Anda dapat memberikan ulasan.',
        ]);
    }
}
