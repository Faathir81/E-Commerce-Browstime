<?php

namespace App\Filament\Produksi\Resources\Produksis\Pages;

use App\Filament\Produksi\Resources\Produksis\ProduksiResource;
use App\Filament\Produksi\Resources\Produksis\Schemas\ProduksiHelper;
use App\Models\BahanBaku;
use App\Models\MutasiStok;
use App\Models\Pesanan;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

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
                ->modalHeading('Mulai produksi?')
                ->modalDescription('Stok bahan baku akan berkurang dan aksi tidak dapat dibatalkan.')
                ->visible(fn () => $this->record->status === 'paid')
                ->action(function () {
                    /** @var Pesanan $pesanan */
                    $pesanan = $this->record->fresh();

                    if ($pesanan?->status !== 'paid') {
                        return $this->notifyStatusInvalid('Aksi Mulai Produksi hanya dapat dijalankan saat status pesanan Terbayar.');
                    }

                    $kebutuhan = $this->hitungKebutuhanBahan($pesanan);

                    // 1) Validasi stok cukup
                    foreach ($kebutuhan as $item) {
                        /** @var BahanBaku $bahan */
                        $bahan = $item['bahan'];
                        $kebutuhanQty = $item['kebutuhan'];

                        if ($bahan->stok_virtual < $kebutuhanQty) {
                            return $this->notifyStockNotEnough($bahan->nama, $kebutuhanQty, $bahan->satuan?->nama, $bahan->stok_virtual);
                        }
                    }

                    try {
                        DB::transaction(function () use ($kebutuhan, $pesanan) {
                            $lockedPesanan = Pesanan::lockForUpdate()->find($pesanan->id);

                            if (!$lockedPesanan || $lockedPesanan->status !== 'paid') {
                                throw new RuntimeException('Aksi tidak valid: status pesanan sudah berubah.');
                            }

                            // 2) Catat mutasi pemakaian produksi
                            foreach ($kebutuhan as $item) {
                                $kebutuhanQty = $item['kebutuhan'];
                                $bahan = BahanBaku::lockForUpdate()->find($item['bahan']->id);

                                if (!$bahan) {
                                    continue;
                                }

                                $stokAwal = $bahan->current_stok;

                                if ($stokAwal < $kebutuhanQty) {
                                    throw new RuntimeException("Stok {$bahan->nama} tidak cukup saat transaksi dijalankan.");
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
                            $lockedPesanan->update(['status' => 'produksi']);
                        });
                    } catch (RuntimeException $e) {
                        $this->notifyStatusInvalid($e->getMessage());
                        return;
                    } catch (Throwable $e) {
                        $this->notifyTransactionError($e->getMessage());
                        return;
                    }

                    $this->notifySuccess('Produksi berhasil diproses');

                    $this->refreshRecordState(forceRedirect: true);
                }),
        ];
    }

    protected function hitungKebutuhanBahan(Pesanan $pesanan): array
    {
        return ProduksiHelper::hitungKebutuhanBahan($pesanan);
    }

    protected function refreshRecordState(bool $forceRedirect = false): void
    {
        if (! $this->record?->getKey()) {
            return;
        }

        $this->record = $this->resolveRecord($this->record->getKey());

        if ($forceRedirect) {
            $this->redirect(static::getResource()::getUrl('view', ['record' => $this->record]));

            return;
        }

        $this->dispatch('$refresh');
    }

    protected function notifyStatusInvalid(string $message): void
    {
        Notification::make()
            ->title('Aksi tidak valid')
            ->body($message)
            ->danger()
            ->send();
    }

    protected function notifyTransactionError(string $message): void
    {
        Notification::make()
            ->title('Terjadi kesalahan')
            ->body($message)
            ->danger()
            ->send();
    }

    protected function notifyStockNotEnough(string $bahan, float $kebutuhan, ?string $satuan, float $stok): void
    {
        $unit = $satuan ? " {$satuan}" : '';

        Notification::make()
            ->title('Stok tidak cukup')
            ->body("Bahan {$bahan} kurang. Dibutuhkan {$kebutuhan}{$unit}, stok sekarang {$stok}.")
            ->danger()
            ->send();
    }

    protected function notifySuccess(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }
}
