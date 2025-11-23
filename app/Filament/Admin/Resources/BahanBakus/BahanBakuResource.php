<?php

namespace App\Filament\Admin\Resources\BahanBakus;

use App\Filament\Admin\Resources\BahanBakus\Pages\CreateBahanBaku;
use App\Filament\Admin\Resources\BahanBakus\Pages\EditBahanBaku;
use App\Filament\Admin\Resources\BahanBakus\Pages\ListBahanBakus;
use App\Filament\Admin\Resources\BahanBakus\Schemas\BahanBakuForm;
use App\Filament\Admin\Resources\BahanBakus\Tables\BahanBakusTable;
use App\Models\BahanBaku;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Bahan Baku';
    protected static ?string $pluralLabel = 'Bahan Baku';
    protected static ?string $modelLabel = 'Bahan Baku';
    protected static ?string $pluralModelLabel = 'Bahan Baku';

    public static function form(Schema $schema): Schema
    {
        return BahanBakuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BahanBakusTable::configure($table);
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
            'index' => ListBahanBakus::route('/'),
            'create' => CreateBahanBaku::route('/create'),
            'edit' => EditBahanBaku::route('/{record}/edit'),
        ];
    }
}
