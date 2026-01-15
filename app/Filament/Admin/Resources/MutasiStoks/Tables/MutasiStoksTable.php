<?php

namespace App\Filament\Admin\Resources\MutasiStoks\Tables;

use App\Filament\Admin\Resources\MutasiStoks\MutasiStokResource;
use App\Support\StatusStyle;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class MutasiStoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bahan.nama')
                    ->label('Bahan'),

                TextColumn::make('bahan.satuan.nama')
                    ->label('Satuan'),

                TextColumn::make('jenis_mutasi')
                    ->label('Mutasi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => StatusStyle::mutasiStokLabel($state))
                    ->color(fn ($state) => StatusStyle::mutasiStok($state)['color'] ?? 'gray'),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->formatStateUsing(fn ($state) => self::formatQuantity($state)),

                TextColumn::make('stok_awal')
                    ->label('Stok Awal')
                    ->formatStateUsing(fn ($state) => self::formatQuantity($state)),

                TextColumn::make('stok_akhir')
                    ->label('Stok Akhir')
                    ->formatStateUsing(fn ($state) => self::formatQuantity($state)),

                TextColumn::make('user.name')
                    ->label('User'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal'),
            ])
            ->recordUrl(fn ($record) => MutasiStokResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function formatQuantity(int|float|string|null $value): string
    {
        $number = is_numeric($value) ? (float) $value : 0.0;
        $formatted = number_format($number, 2, ',', '.');

        return rtrim(rtrim($formatted, '0'), ',');
    }
}
