<?php

namespace App\Filament\Admin\Resources\Pesanans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
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
                    ->label('Qty')
                    ->summarize(
                        Summarizer::make('total')
                            ->label('Total')
                            ->using(fn () => (float) ($this->getOwnerRecord()?->total ?? 0))
                            ->money('IDR')
                    ),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->summarize(
                        Summarizer::make('ongkir')
                            ->label('Ongkir')
                            ->using(fn () => (float) ($this->getOwnerRecord()?->ongkir ?? 0))
                            ->money('IDR')
                    ),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->summarize(
                        Sum::make()->label('Subtotal')->money('IDR')
                    ),
            ])
            ->headerActions([])      // tetap sama
            ->recordActions([]);     // ganti dari ->actions([])
    }
}
