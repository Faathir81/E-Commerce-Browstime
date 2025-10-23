<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_name')
                    ->label('Customer Name')
                    ->sortable(),
                TextColumn::make('customer_email')
                    ->label('Customer Email')
                    ->sortable(),
                TextColumn::make('customer_phone')
                    ->label('Customer Phone')
                    ->sortable(),
                TextColumn::make('shipping_address')
                    ->label('Shipping Address')
                    ->sortable(),
                TextColumn::make('estimated_delivery_at')
                    ->label('Estimated Delivery')
                    ->sortable(),
            ])
            ->filters([
                //
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
