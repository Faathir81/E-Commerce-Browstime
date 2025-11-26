<?php

namespace App\Filament\Admin\Resources\Pesanans;

use App\Filament\Admin\Resources\Pesanans\Pages\CreatePesanan;
use App\Filament\Admin\Resources\Pesanans\Pages\EditPesanan;
use App\Filament\Admin\Resources\Pesanans\Pages\ListPesanans;
use App\Filament\Admin\Resources\Pesanans\Pages\ViewPesanan;
use App\Filament\Admin\Resources\Pesanans\Schemas\PesananForm;
use App\Filament\Admin\Resources\Pesanans\Schemas\PesananInfolist;
use App\Filament\Admin\Resources\Pesanans\Tables\PesanansTable;
use App\Models\Pesanan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $recordTitleAttribute = 'kode';

    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $pluralLabel = 'Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';

    public static function form(Schema $schema): Schema
    {
        return PesananForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PesananInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PesanansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\Pesanans\RelationManagers\DetailPesanansRelationManager::class,
            \App\Filament\Admin\Resources\Pesanans\RelationManagers\PembayaranRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => ListPesanans::route('/'),
            'create' => CreatePesanan::route('/create'),
            'view' => ViewPesanan::route('/{record}'),
            'edit' => EditPesanan::route('/{record}/edit'),
        ];
    }
}
