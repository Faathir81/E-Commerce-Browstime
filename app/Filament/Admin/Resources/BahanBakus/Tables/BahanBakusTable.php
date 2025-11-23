<?php

namespace App\Filament\Admin\Resources\BahanBakus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BahanBakusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('satuan.nama')
                    ->label('Satuan')
                    ->sortable(),

                TextColumn::make('stok_awal')
                    ->label('Stok Awal')
                    ->formatStateUsing(function ($state) {
                        $value = (string) $state;
                        return str_contains($value, '.')
                            ? rtrim(rtrim($value, '0'), '.')
                            : $value;
                    })
                    ->color(fn ($record) =>
                        $record->stok_awal < $record->stok_minimum ? 'danger' : 'success'
                    ),

                TextColumn::make('stok_minimum')
                    ->label('Stok Minimum')
                    ->formatStateUsing(function ($state) {
                        $value = (string) $state;
                        return str_contains($value, '.')
                            ? rtrim(rtrim($value, '0'), '.')
                            : $value;
                    }),
            ])
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
