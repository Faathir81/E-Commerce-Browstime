<?php

namespace App\Filament\Admin\Resources\Pesanans\Pages;

use App\Filament\Admin\Resources\Pesanans\PesananResource;
use App\Models\Pesanan;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $currentStatus = $this->record->status ?? null;
        $newStatus = $data['status'] ?? $currentStatus;

        $this->assertStatusTransitionAllowed($currentStatus, $newStatus);

        $data['status'] = $newStatus;

        return $data;
    }

    protected function assertStatusTransitionAllowed(?string $currentStatus, ?string $newStatus): void
    {
        if ($newStatus === null || $newStatus === $currentStatus) {
            return;
        }

        if ($newStatus === Pesanan::STATUS_PRODUKSI) {
            throw ValidationException::withMessages([
                'status' => 'Status Diproduksi hanya dapat diatur oleh staf produksi.',
            ]);
        }

        if ($newStatus === Pesanan::STATUS_SELESAI) {
            throw ValidationException::withMessages([
                'status' => 'Status Selesai hanya dapat dikonfirmasi oleh pelanggan.',
            ]);
        }

        if ($newStatus === Pesanan::STATUS_BATAL) {
            throw ValidationException::withMessages([
                'status' => 'Status Batal tidak dapat diubah melalui panel admin.',
            ]);
        }

        if ($newStatus === Pesanan::STATUS_DIKIRIM && $currentStatus !== Pesanan::STATUS_PRODUKSI) {
            throw ValidationException::withMessages([
                'status' => 'Status Dikirim hanya dapat dipilih setelah pesanan Diproduksi.',
            ]);
        }
    }
}
