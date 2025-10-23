<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
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

    public static function table(Table $table): Table
    {
        return $table->columns([
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
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
