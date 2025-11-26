<?php

namespace App\Filament\Admin\Resources\ResepBOMS;

use App\Filament\Admin\Resources\ResepBOMS\Pages\CreateResepBOM;
use App\Filament\Admin\Resources\ResepBOMS\Pages\EditResepBOM;
use App\Filament\Admin\Resources\ResepBOMS\Pages\ListResepBOMS;
use App\Filament\Admin\Resources\ResepBOMS\Schemas\ResepBOMForm;
use App\Filament\Admin\Resources\ResepBOMS\Tables\ResepBOMSTable;
use App\Models\ResepBOM;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResepBOMResource extends Resource
{
    protected static ?string $model = ResepBOM::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'produk.nama';

    protected static string | UnitEnum | null $navigationGroup = 'Produksi';

    protected static ?string $modelLabel = 'Resep BOM';
    protected static ?string $pluralModelLabel = 'Resep BOM';

    public static function form(Schema $schema): Schema
    {
        return ResepBOMForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResepBOMSTable::configure($table);
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
            'index' => ListResepBOMS::route('/'),
            'create' => CreateResepBOM::route('/create'),
            'edit' => EditResepBOM::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
