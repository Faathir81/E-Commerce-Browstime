<?php

namespace App\Filament\Resources\Payments\Payments\Pages;

use App\Filament\Resources\Payments\Payments\PaymentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

use Filament\Actions\Action;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addPayment')
                ->label('Tambah Pembayaran')
                ->icon('heroicon-o-credit-card')
                ->url(fn ($record) => PaymentResource::getUrl('create', [
                    'order_id' => $record->id,
                ]))
                ->hidden(fn ($record) => $record->status === 'paid'),
        ];
    }
}