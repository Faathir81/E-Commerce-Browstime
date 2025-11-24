<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen;

use App\Filament\Admin\Resources\WilayahPengirimen\Pages\CreateWilayahPengiriman;
use App\Filament\Admin\Resources\WilayahPengirimen\Pages\EditWilayahPengiriman;
use App\Filament\Admin\Resources\WilayahPengirimen\Pages\ListWilayahPengirimen;
use App\Filament\Admin\Resources\WilayahPengirimen\Schemas\WilayahPengirimanForm;
use App\Filament\Admin\Resources\WilayahPengirimen\Tables\WilayahPengirimenTable;
use App\Models\WilayahPengiriman;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WilayahPengirimanResource extends Resource
{
    protected static ?string $model = WilayahPengiriman::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Wilayah Pengiriman';
    protected static ?string $pluralLabel = 'Wilayah Pengiriman';
    protected static ?string $modelLabel = 'Wilayah Pengiriman';
    protected static ?string $pluralModelLabel = 'Wilayah Pengiriman';

    public static function form(Schema $schema): Schema
    {
        return WilayahPengirimanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WilayahPengirimenTable::configure($table);
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
            'index' => ListWilayahPengirimen::route('/'),
            'create' => CreateWilayahPengiriman::route('/create'),
            'edit' => EditWilayahPengiriman::route('/{record}/edit'),
        ];
    }
}
