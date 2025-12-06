<?php

namespace App\Filament\Admin\Resources\Kategoris;

use App\Filament\Admin\Resources\Kategoris\Pages\CreateKategori;
use App\Filament\Admin\Resources\Kategoris\Pages\EditKategori;
use App\Filament\Admin\Resources\Kategoris\Pages\ListKategoris;
use App\Filament\Admin\Resources\Kategoris\Schemas\KategoriForm;
use App\Filament\Admin\Resources\Kategoris\Tables\KategorisTable;
use App\Models\Kategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Clusters\ProdukKatalog\ProdukKatalogCluster;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralLabel = 'Kategori';
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';

    protected static ?string $cluster = ProdukKatalogCluster::class;

    public static function form(Schema $schema): Schema
    {
        return KategoriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategorisTable::configure($table);
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
            'index' => ListKategoris::route('/'),
            'create' => CreateKategori::route('/create'),
            'edit' => EditKategori::route('/{record}/edit'),
        ];
    }
}
