<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderCompletionController extends Controller
{
    public function __invoke(string $kode): RedirectResponse
    {
        $pesanan = Pesanan::where('kode', $kode)->firstOrFail();

        try {
            $alreadyCompleted = false;

            DB::transaction(function () use ($pesanan, &$alreadyCompleted) {
                $locked = Pesanan::lockForUpdate()->find($pesanan->id);

                if (! $locked) {
                    abort(404);
                }

                if ($locked->status === Pesanan::STATUS_SELESAI) {
                    $alreadyCompleted = true;
                    return;
                }

                if ($locked->status !== Pesanan::STATUS_DIKIRIM) {
                    abort(422, 'Konfirmasi hanya dapat dilakukan saat pesanan berstatus Dikirim.');
                }

                $locked->update([
                    'status' => Pesanan::STATUS_SELESAI,
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()
                ->route('order.success', ['kode' => $kode])
                ->with('error', 'Konfirmasi gagal: ' . $e->getMessage());
        }

        $message = ($alreadyCompleted ?? false)
            ? 'Pesanan sudah dikonfirmasi selesai.'
            : 'Pesanan telah dikonfirmasi selesai. Terima kasih!';

        return redirect()
            ->route('order.success', ['kode' => $kode])
            ->with('status', $message);
    }
}
