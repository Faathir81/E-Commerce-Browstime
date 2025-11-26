<?php

namespace App\Filament\Admin\Resources\Pesanans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DetailPesanansRelationManager extends RelationManager
{
    protected static string $relationship = 'detailPesanans';
    protected static ?string $title = 'Item Pesanan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama')
                    ->label('Produk'),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Qty'),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR'),
            ])
            ->headerActions([])      // tetap sama
            ->recordActions([]);     // ganti dari ->actions([])
    }
}
