<?php

namespace App\Filament\Produksi\Resources\Produksis\Tables;

use App\Support\StatusStyle;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;

class ProduksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode Pesanan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => StatusStyle::pesanan($state)['label'])
                    ->color(fn (?string $state) => StatusStyle::pesanan($state)['color'])
                    ->icon(fn (?string $state) => StatusStyle::pesanan($state)['icon'])
                    ->label('Status')
                    ->toggleable(),
            ])

            ->recordActions([
                ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye'),
            ])

            ->toolbarActions([
                // staf produksi tidak boleh bulk delete
                // Kosongkan array jika tidak ada bulk actions
            ]);
    }
}
