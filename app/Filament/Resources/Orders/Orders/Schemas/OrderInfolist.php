<?php

namespace App\Filament\Resources\Orders\Orders\Schemas;

use Filament\Schemas\Schema;                      // ⬅️ schema builder
use Filament\Schemas\Components\Section; 
use Filament\Infolists\Components\TextEntry;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
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
}
