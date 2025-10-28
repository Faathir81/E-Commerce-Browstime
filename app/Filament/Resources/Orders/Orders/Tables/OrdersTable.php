<?php

namespace App\Filament\Resources\Orders\Orders\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Table;

use Filament\Tables;
use Filament\Actions\Action;
use App\Filament\Resources\Orders\Orders\OrderResource; // untuk panggil nextStatus()
use App\Models\Order;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('buyer_name')
                    ->label('Buyer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('buyer_phone')
                    ->label('Phone'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray'   => ['pending','awaiting_payment','paid'],
                        'warning'=> 'processing',
                        'info'   => 'shipped',
                        'success'=> 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')->money('idr', true),
                Tables\Columns\TextColumn::make('shipping_cost')->money('idr', true),
                Tables\Columns\TextColumn::make('grand_total')->money('idr', true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('revertStatus')
                    ->label(function (Order $record) {
                        return match (OrderResource::prevStatus($record->status)) {
                            'pending'     => 'Undo Confirm',
                            'processing'  => 'Undo Ship',
                            'shipped'     => 'Undo Done',
                            default       => 'Revert',
                        };
                    })
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray')
                    ->visible(fn (Order $record) => OrderResource::prevStatus($record->status) !== null)
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        if ($prev = OrderResource::prevStatus($record->status)) {
                            $record->update(['status' => $prev]);
                        }
                    }),

                Action::make('advanceStatus')
                    ->label(function (Order $record) {
                        return match (OrderResource::nextStatus($record->status)) {
                            'processing' => 'Confirm',
                            'shipped'    => 'Ship',
                            'delivered'  => 'Mark as Done',
                            default      => 'Advance',
                        };
                    })
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('primary')
                    ->visible(fn (Order $record) => OrderResource::nextStatus($record->status) !== null)
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        if ($next = OrderResource::nextStatus($record->status)) {
                            $record->update(['status' => $next]);
                        }
                    }),
                ])
            ->toolbarActions([]);
    }
}
