<?php

namespace App\Filament\Admin\Resources\MutasiStoks;

use App\Filament\Admin\Resources\MutasiStoks\Pages\CreateMutasiStok;
use App\Filament\Admin\Resources\MutasiStoks\Pages\ListMutasiStoks;
use App\Filament\Admin\Resources\MutasiStoks\Pages\ViewMutasiStok;
use App\Filament\Admin\Resources\MutasiStoks\Schemas\MutasiStokForm;
use App\Filament\Admin\Resources\MutasiStoks\Tables\MutasiStoksTable;
use App\Models\MutasiStok;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MutasiStokResource extends Resource
{
    protected static ?string $model = MutasiStok::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $recordTitleAttribute = 'bahan.nama';

    protected static string | UnitEnum | null $navigationGroup = 'Produksi';

    protected static ?string $navigationLabel = 'Mutasi Stok';
    protected static ?string $pluralLabel = 'Mutasi Stok';
    protected static ?string $modelLabel = 'Mutasi Stok';
    protected static ?string $pluralModelLabel = 'Mutasi Stok';

    public static function form(Schema $schema): Schema
    {
        return MutasiStokForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MutasiStoksTable::configure($table);
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
            'index' => ListMutasiStoks::route('/'),
            'create' => CreateMutasiStok::route('/create'),
            'view' => ViewMutasiStok::route('/{record}'),
        ];
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
