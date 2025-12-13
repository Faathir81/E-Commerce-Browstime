<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUlasanRequest;
use App\Models\DetailPesanan;
use App\Models\Ulasan;
use App\Support\ReviewGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    public function store(StoreUlasanRequest $request): RedirectResponse
    {
        $detail = $request->detailPesanan();

        if (! $detail) {
            abort(422, 'Detail pesanan tidak valid.');
        }

        $guestEmail = $request->validated('guest_email');

        DB::transaction(function () use ($detail, $request, $guestEmail) {
            $lockedDetail = DetailPesanan::with(['pesanan', 'ulasan'])
                ->lockForUpdate()
                ->find($detail->id);

            if (! $lockedDetail) {
                abort(404);
            }

            if (! ReviewGuard::canReviewDetail($lockedDetail, $request->user(), $guestEmail)) {
                abort(403, 'Anda tidak diizinkan mengulas item ini.');
            }

            if ($lockedDetail->hasUlasan()) {
                abort(422, 'Item ini sudah memiliki ulasan.');
            }

            $pelanggan = ReviewGuard::resolveAuthorPelanggan($lockedDetail);
            if (! $pelanggan) {
                abort(422, 'Data pelanggan tidak valid untuk ulasan.');
            }

            Ulasan::create([
                'pelanggan_id' => $pelanggan->id,
                'detail_pesanan_id' => $lockedDetail->id,
                'rating' => (int) $request->validated('rating'),
                'komentar' => $request->validated('komentar'),
            ]);
        });

        return back()->with('status', 'Terima kasih, ulasan Anda telah disimpan.');
    }
}
