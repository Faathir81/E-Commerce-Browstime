<?php

namespace App\Filament\Resources\Orders\Orders;

use App\Filament\Resources\Orders\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Orders\Pages\ViewOrder;
use App\Filament\Resources\Orders\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Orders\Schemas\OrderInfolist;
use App\Filament\Resources\Orders\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string | UnitEnum | null $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
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
            // 'create' => CreateOrder::route('/create'),
            'view' => ViewOrder::route('/{record}'),
            // 'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function nextStatus(?string $current): ?string
    {
        return [
            'pending'          => 'processing', // confirm
            'awaiting_payment' => 'processing', // kalau dipakai
            'paid'             => 'processing',
            'processing'       => 'shipped',    // ship
            'shipped'          => 'delivered',  // done
        ][$current ?? ''] ?? null;
    }

    public static function prevStatus(?string $current): ?string
    {
        return [
            'processing' => 'pending',     // undo confirm
            'shipped'    => 'processing',  // undo ship
            'delivered'  => 'shipped',     // undo done
        ][$current ?? ''] ?? null;
    }
}
