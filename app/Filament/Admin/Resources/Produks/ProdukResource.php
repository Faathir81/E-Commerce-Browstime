<?php

namespace App\Filament\Admin\Resources\Produks;

use App\Filament\Admin\Resources\Produks\Pages\CreateProduk;
use App\Filament\Admin\Resources\Produks\Pages\EditProduk;
use App\Filament\Admin\Resources\Produks\Pages\ListProduks;
use App\Filament\Admin\Resources\Produks\Schemas\ProdukForm;
use App\Filament\Admin\Resources\Produks\Tables\ProduksTable;
use App\Models\Produk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Clusters\ProdukKatalog\ProdukKatalogCluster;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $pluralLabel = 'Produk';
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';

    protected static ?string $cluster = ProdukKatalogCluster::class;

    public static function form(Schema $schema): Schema
    {
        return ProdukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduksTable::configure($table);
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
            'index' => ListProduks::route('/'),
            'create' => CreateProduk::route('/create'),
            'edit' => EditProduk::route('/{record}/edit'),
        ];
    }
}
