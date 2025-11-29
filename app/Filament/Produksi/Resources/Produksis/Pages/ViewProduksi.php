<?php

namespace App\Filament\Produksi\Resources\Produksis\Pages;

use App\Filament\Produksi\Resources\Produksis\ProduksiResource;
use App\Filament\Produksi\Resources\Produksis\Schemas\ProduksiHelper;
use App\Models\BahanBaku;
use App\Models\DetailPesanan;
use App\Models\MutasiStok;
use App\Models\Pesanan;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewProduksi extends ViewRecord
{
    protected static string $resource = ProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('proses_produksi')
                ->label('Mulai Produksi')
                ->color('warning')
                ->icon('heroicon-o-fire')
                ->requiresConfirmation()
                ->visible(fn () => in_array($this->record->status, ['paid', 'produksi']))
                ->action(function () {
                    /** @var Pesanan $pesanan */
                    $pesanan = $this->record;

                    $kebutuhan = $this->hitungKebutuhanBahan($pesanan);

                    // 1) Validasi stok cukup
                    foreach ($kebutuhan as $item) {
                        /** @var BahanBaku $bahan */
                        $bahan = $item['bahan'];
                        $kebutuhanQty = $item['kebutuhan'];

                        if ($bahan->stok_virtual < $kebutuhanQty) {
                            Notification::make()
                                ->title('Stok tidak cukup')
                                ->body("Bahan {$bahan->nama} kurang. Dibutuhkan {$kebutuhanQty} {$bahan->satuan?->nama}, stok sekarang {$bahan->stok_virtual}.")
                                ->danger()
                                ->send();

                            return;
                        }
                    }

                    DB::transaction(function () use ($kebutuhan, $pesanan) {
                        // 2) Catat mutasi pemakaian produksi
                        foreach ($kebutuhan as $item) {
                            $kebutuhanQty = $item['kebutuhan'];
                            $bahan = BahanBaku::lockForUpdate()->find($item['bahan']->id);

                            if (!$bahan) {
                                continue;
                            }

                            $stokAwal = $bahan->current_stok;

                            if ($stokAwal < $kebutuhanQty) {
                                throw new \RuntimeException("Stok {$bahan->nama} tidak cukup saat transaksi dijalankan.");
                            }

                            $stokAkhir = $stokAwal - $kebutuhanQty;

                            MutasiStok::create([
                                'bahan_id' => $bahan->id,
                                'jenis_mutasi' => 'pemakaian_produksi',
                                'qty' => $kebutuhanQty,
                                'stok_awal' => $stokAwal,
                                'stok_akhir' => $stokAkhir,
                                'catatan' => 'Produksi pesanan ' . $pesanan->kode,
                                'user_id' => Auth::id(),
                            ]);

                            // stok_awal disimpan sebagai stok berjalan (current_stok)
                            $bahan->update([
                                'stok_awal' => $stokAkhir,
                            ]);
                        }

                        // 3) Update status pesanan jika masih paid
                        if ($pesanan->status === 'paid') {
                            $pesanan->update(['status' => 'produksi']);
                        }
                    });

                    Notification::make()
                        ->title('Produksi berhasil diproses')
                        ->success()
                        ->send();
                })
                ->after(fn () => $this->fillForm()), // ✅ Perbaikan: tambahkan after hook

            Action::make('siap_dikirim')
                ->label('Tandai Siap Dikirim')
                ->color('success')
                ->icon('heroicon-o-truck')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'produksi')
                ->action(function () {
                    $this->record->update(['status' => 'dikirim']);

                    Notification::make()
                        ->title('Pesanan ditandai siap dikirim')
                        ->success()
                        ->send();
                })
                ->after(fn () => $this->fillForm()), // ✅ Perbaikan: tambahkan after hook
        ];
    }

    protected function hitungKebutuhanBahan(Pesanan $pesanan): array
    {
        return ProduksiHelper::hitungKebutuhanBahan($pesanan);
    }
}
