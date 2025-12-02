<?php

namespace App\Filament\Admin\Resources\Pesanans\Tables;

use App\Models\Pesanan;
use App\Support\StatusStyle;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\{Action, EditAction, DeleteBulkAction, BulkActionGroup};

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => StatusStyle::pesanan($state)['label'])
                    ->color(fn (?string $state) => StatusStyle::pesanan($state)['color'])
                    ->icon(fn (?string $state) => StatusStyle::pesanan($state)['icon']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->recordUrl(fn (Pesanan $record) => \App\Filament\Admin\Resources\Pesanans\PesananResource::getUrl('edit', ['record' => $record]))
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
