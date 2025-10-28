<?php

namespace App\Filament\Resources\Payments\Payments\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;


class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Payment Data')
                ->schema([
                    Select::make('order_id')
                        ->label('Order')
                        ->relationship('order', 'id')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->hidden(fn () => request()->has('order_id')),

                    TextInput::make('amount')
                        ->label('Amount (Rp)')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    Select::make('method')
                        ->label('Method')
                        ->options([
                            'transfer' => 'Transfer',
                            'qris'     => 'QRIS',
                            'cash'     => 'Cash',
                        ])
                        ->required(),

                    Select::make('gateway')->label('Provider')
                        ->options(['manual' => 'Manual', 'midtrans' => 'Midtrans'])
                        ->default('manual')
                        ->required(),

                    Select::make('status')->label('Status')
                        ->options(['pending' => 'Pending', 'verified' => 'Verified', 'failed' => 'Failed'])
                        ->default('pending')
                        ->required(),

                    DateTimePicker::make('paid_at')
                        ->label('Paid At')
                        ->seconds(false)
                        ->native(false),
                ])
                ->columns(2),

            Section::make('Gateway & Meta')
                ->schema([
                    TextInput::make('currency')->default('IDR')->maxLength(3),
                    TextInput::make('channel')->label('Channel')->maxLength(50)->nullable(),
                    TextInput::make('gateway_order_id')->label('Gateway Order ID')->maxLength(64)->nullable(),
                    TextInput::make('transaction_id')->label('Transaction ID')->maxLength(64)->nullable(),
                    TextInput::make('va_number')->label('VA Number')->maxLength(50)->nullable(),
                    TextInput::make('qr_string')->label('QR String')->nullable(),
                ])
                ->columns(2),

            Section::make('Proof')
                ->schema([
                    FileUpload::make('proof_url')
                        ->label('Bukti Transfer')
                        ->disk('public')
                        ->directory('payments/proofs')
                        ->visibility('public')
                        ->preserveFilenames(false)
                        ->getUploadedFileNameForStorageUsing(
                            fn ($file) => uniqid() . '.' . $file->getClientOriginalExtension()
                        )
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrateStateUsing(fn ($state, $record) => $state ?? $record?->proof_url),
                ])
                ->collapsible(),
        ]);
    }
}