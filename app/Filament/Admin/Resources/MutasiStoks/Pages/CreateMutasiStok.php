<?php

namespace App\Filament\Admin\Resources\MutasiStoks\Pages;

use App\Filament\Admin\Resources\MutasiStoks\MutasiStokResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMutasiStok extends CreateRecord
{
    protected static string $resource = MutasiStokResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $bahan = \App\Models\BahanBaku::find($data['bahan_id']);

        $data['stok_awal'] = $bahan->stok_awal;

        // rumus mutasi
        if ($data['jenis_mutasi'] === 'penyesuaian') {
            $data['stok_akhir'] = $data['qty'];
        } elseif ($data['jenis_mutasi'] === 'stok_masuk') {
            $data['stok_akhir'] = $bahan->stok_awal + $data['qty'];
        } else {
            $data['stok_akhir'] = $bahan->stok_awal - $data['qty'];
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $bahan = $this->record->bahan;
        $bahan->update([
            'stok_awal' => $this->record->stok_akhir
        ]);
    }
}
