<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\Midtrans\MidtransPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class MidtransPaymentController extends Controller
{
    public function __construct(private MidtransPaymentService $paymentService)
    {
    }

    public function store(string $kode): RedirectResponse
    {
        $pesanan = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
            ->where('kode', $kode)
            ->first();

        if (! $pesanan) {
            abort(404);
        }

        if ($pesanan->status !== Pesanan::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibayar dengan Midtrans.');
        }

        try {
            $transaction = $this->paymentService->createTransaction($pesanan);
        } catch (\Throwable $th) {
            report($th);

            return redirect()->back()->with('error', 'Gagal membuat transaksi Midtrans. Silakan coba lagi.');
        }

        $response = $transaction['response'] ?? [];
        $redirectUrl = $response['redirect_url'] ?? null;

        $pembayaranData = [
            'pesanan_id' => $pesanan->id,
            'metode' => 'midtrans',
            'jumlah' => $transaction['gross_amount'],
            'status' => 'pending',
            'midtrans_order_id' => $pesanan->kode,
            'midtrans_transaction_id' => $response['transaction_id'] ?? null,
            'akun_bank_id' => null,
            'qris_setting_id' => null,
        ];

        if (Schema::hasColumn('pembayarans', 'snap_token')) {
            $pembayaranData['snap_token'] = $response['token'] ?? null;
        }

        if (Schema::hasColumn('pembayarans', 'snap_redirect_url')) {
            $pembayaranData['snap_redirect_url'] = $redirectUrl;
        }

        if ($pesanan->pembayaran) {
            $pesanan->pembayaran->update($pembayaranData);
        } else {
            Pembayaran::create($pembayaranData);
        }

        if (! $redirectUrl) {
            return redirect()->back()->with('error', 'URL pembayaran Midtrans tidak tersedia.');
        }

        return redirect()->away($redirectUrl);
    }

    public function finish(string $kode): RedirectResponse
    {
        $pesanan = Pesanan::where('kode', $kode)->first();

        if (! $pesanan) {
            return redirect()->route('landing')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        // Status akhir tetap menunggu webhook; hanya arahkan user kembali ke order detail/success.
        Log::info('Midtrans finish callback received', [
            'kode' => $kode,
            'query' => request()->query(),
        ]);

        return redirect()->route('order.success', ['kode' => $kode])
            ->with('info', 'Pembayaran sedang diproses. Silakan cek status pesanan Anda.');
    }
}
