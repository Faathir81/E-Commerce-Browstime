<?php

namespace App\Filament\Admin\Resources\Satuans;

use App\Filament\Admin\Resources\Satuans\Pages\CreateSatuan;
use App\Filament\Admin\Resources\Satuans\Pages\EditSatuan;
use App\Filament\Admin\Resources\Satuans\Pages\ListSatuans;
use App\Filament\Admin\Resources\Satuans\Schemas\SatuanForm;
use App\Filament\Admin\Resources\Satuans\Tables\SatuansTable;
use App\Models\Satuan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SatuanResource extends Resource
{
    protected static ?string $model = Satuan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Satuan';
    protected static ?string $pluralLabel = 'Satuan';
    protected static ?string $modelLabel = 'Satuan';
    protected static ?string $pluralModelLabel = 'Satuan';

    public static function form(Schema $schema): Schema
    {
        return SatuanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SatuansTable::configure($table);
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
            'index' => ListSatuans::route('/'),
            'create' => CreateSatuan::route('/create'),
            'edit' => EditSatuan::route('/{record}/edit'),
        ];
    }
}
