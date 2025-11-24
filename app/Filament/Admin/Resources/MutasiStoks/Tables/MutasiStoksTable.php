<?php

namespace App\Filament\Admin\Resources\MutasiStoks\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MutasiStoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bahan.nama')
                    ->label('Bahan'),

                TextColumn::make('jenis_mutasi')
                    ->label('Mutasi')
                    ->badge(),

                TextColumn::make('qty')
                    ->numeric()
                    ->label('Qty'),

                TextColumn::make('stok_awal')
                    ->numeric()
                    ->label('Stok Awal'),

                TextColumn::make('stok_akhir')
                    ->numeric()
                    ->label('Stok Akhir'),

                TextColumn::make('user.name')
                    ->label('User'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
