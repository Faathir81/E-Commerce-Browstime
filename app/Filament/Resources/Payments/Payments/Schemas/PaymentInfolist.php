<?php

namespace App\Filament\Resources\Payments\Payments\Schemas;

use Filament\Schemas\Schema;

use App\Models\Payment;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Payment Info')
                ->schema([
                    TextEntry::make('order_id')->label('Order'),

                    // Jika mau angka rupiah yang rapi, boleh tetap pakai money():
                    TextEntry::make('amount')->label('Amount')->money('IDR', true),

                    TextEntry::make('method')->label('Method'),
                    TextEntry::make('gateway')->label('Provider'),

                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'verified' => 'success',
                            'pending'  => 'warning',
                            'failed'   => 'danger',
                            default    => 'gray',
                        }),

                    TextEntry::make('paid_at')->label('Paid At')->dateTime('d M Y H:i'),
                    TextEntry::make('created_at')->label('Created')->since(),
                ])
                ->columns(2),

            Section::make('Bukti Pembayaran')
                ->schema([
                    ImageEntry::make('proof_url')
                        ->label('Proof')
                        ->disk('public')
                        ->visibility('public')
                        ->hidden(fn (?Payment $record) => blank($record?->proof_url)),

                    TextEntry::make('proof_url')
                        ->label('File Path')
                        ->copyable()
                        ->hidden(fn (?Payment $record) => blank($record?->proof_url)),
                ])
                ->collapsible(),
        ]);
    }
}
