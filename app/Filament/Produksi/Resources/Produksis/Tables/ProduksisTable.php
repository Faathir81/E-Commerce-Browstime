<?php

namespace App\Filament\Produksi\Resources\Produksis\Tables;

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
                    ->searchable(),

                TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->placeholder('-'),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'warning',
                        'produksi' => 'info',
                        'dikirim' => 'success',
                        default => 'gray',
                    })
                    ->label('Status'),
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
