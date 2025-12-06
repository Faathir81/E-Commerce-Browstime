<?php

namespace App\Filament\Produksi\Resources\Produksis;

use App\Filament\Produksi\Resources\Produksis\Pages\ListProduksi;
use App\Filament\Produksi\Resources\Produksis\Pages\ViewProduksi;
use App\Filament\Produksi\Resources\Produksis\Tables\ProduksisTable;
use App\Filament\Produksi\Resources\Produksis\Schemas\ProduksiInfolist;
use App\Models\Pesanan;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProduksiResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    public const ALLOWED_STATUSES = ['paid', 'produksi', 'dikirim', 'selesai'];

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $recordTitleAttribute = 'kode';

    protected static string | UnitEnum | null $navigationGroup = 'Produksi';

    protected static ?string $navigationLabel = 'Siap Produksi';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProduksiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduksisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('status', self::ALLOWED_STATUSES);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProduksi::route('/'),
            'view'  => ViewProduksi::route('/{record}'),
        ];
    }

    public static function canCreate(): bool { 
        return false; 
    }

    public static function canEdit($record): bool { 
        return false; 
    }

    public static function canDelete($record): bool { 
        return false; 
    }
}
