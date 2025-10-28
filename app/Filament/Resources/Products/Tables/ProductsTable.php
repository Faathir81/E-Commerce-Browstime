<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label('Cover')
                    ->getStateUsing(fn ($record) => $record->coverImage?->url)
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((int) $state, 0, ',', '.')),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
