<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->label('Customer Name')
                    ->required(),
                TextInput::make('customer_email')
                    ->label('Customer Email')
                    ->email()
                    ->required(),
                TextInput::make('customer_phone')
                    ->label('Customer Phone')
                    ->required(),
                Textarea::make('shipping_address')
                    ->label('Shipping Address')
                    ->required(),
                DateTimePicker::make('estimated_delivery_at')
                    ->label('Estimated Delivery')
                    ->nullable(),
            ]);
    }
}
