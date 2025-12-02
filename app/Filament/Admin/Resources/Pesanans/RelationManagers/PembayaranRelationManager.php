<?php

namespace App\Filament\Admin\Resources\Pesanans\RelationManagers;

use App\Support\StatusStyle;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction; // ❌ HAPUS INI - namespace salah

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';
    protected static ?string $title = 'Pembayaran';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode')
                    ->badge(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR'),

                Tables\Columns\ImageColumn::make('bukti_bayar')
                    ->label('Bukti')
                    ->disk('public')
                    ->size(80),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => StatusStyle::pembayaran($state)['label'])
                    ->color(fn (?string $state) => StatusStyle::pembayaran($state)['color'])
                    ->icon(fn (?string $state) => StatusStyle::pembayaran($state)['icon']),
            ])
            ->headerActions([])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        Forms\Components\Select::make('metode')
                            ->label('Metode')
                            ->options([
                                'transfer' => 'Transfer Bank',
                                'qris'     => 'QRIS',
                                'midtrans' => 'Midtrans',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->required(),

                        Forms\Components\FileUpload::make('bukti_bayar')
                            ->label('Bukti Pembayaran')
                            ->disk('public')
                            ->directory('bukti-pembayaran')
                            ->image()
                            ->visibility('public'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(StatusStyle::pembayaranOptions())
                            ->native(false)
                            ->required(),
                    ]),
            ])
            ->toolbarActions([]);
    }
}
