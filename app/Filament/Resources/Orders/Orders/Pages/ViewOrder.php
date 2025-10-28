<?php

namespace App\Filament\Resources\Orders\Orders\Pages;

use App\Filament\Resources\Orders\Orders\OrderResource;
use Filament\Resources\Pages\ViewRecord;

use Filament\Schemas\Components\Section; 
use Filament\Infolists\Components\TextEntry;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Schemas\Schema;


class ViewOrder extends ViewRecord
{
    use InteractsWithTable;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Order')
                ->schema([
                    TextEntry::make('code')->label('Code'),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('grand_total')->money('idr', true),
                    TextEntry::make('created_at')->dateTime(),
                ])->columns(4),

            Section::make('Buyer & Shipping')
                ->schema([
                    TextEntry::make('buyer_name')->label('Buyer'),
                    TextEntry::make('buyer_phone')->label('Phone'),
                    TextEntry::make('ship_address')->label('Address')->columnSpanFull(),
                    TextEntry::make('ship_city')->label('City'),
                    TextEntry::make('ship_postal')->label('Postal'),
                    TextEntry::make('shipping_courier')->label('Courier'),
                    TextEntry::make('shipping_service')->label('Service'),
                    TextEntry::make('shipping_etd')->label('ETD'),
                ])->columns(2),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->heading('Items')
            ->query($this->record->items()->with('product'))
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('qty')->label('Qty'),
                Tables\Columns\TextColumn::make('price_frozen')->label('Price')->money('idr', true),
                Tables\Columns\TextColumn::make('total')
                    ->state(fn ($record) => $record->qty * $record->price_frozen)
                    ->label('Total')->money('idr', true),
            ])
            ->paginated(false)
            ->actions([])
            ->bulkActions([]);
    }
}
