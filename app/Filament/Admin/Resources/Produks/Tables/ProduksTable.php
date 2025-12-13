<?php

namespace App\Filament\Admin\Resources\Produks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->width(60),

                TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', true),

                TextColumn::make('berat')
                    ->label('Berat (gram)')
                    ->suffix(' gr')
                    ->sortable(),

                TextColumn::make('waktu_produksi')
                    ->label('Waktu (menit)')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
