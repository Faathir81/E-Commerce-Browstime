<?php

namespace App\Filament\Admin\Resources\AkunBanks;

use App\Filament\Admin\Resources\AkunBanks\Pages\CreateAkunBank;
use App\Filament\Admin\Resources\AkunBanks\Pages\EditAkunBank;
use App\Filament\Admin\Resources\AkunBanks\Pages\ListAkunBanks;
use App\Filament\Admin\Resources\AkunBanks\Schemas\AkunBankForm;
use App\Filament\Admin\Resources\AkunBanks\Tables\AkunBanksTable;
use App\Models\AkunBank;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AkunBankResource extends Resource
{
    protected static ?string $model = AkunBank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_bank';

    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan Sistem';

    protected static ?string $navigationLabel = 'Akun Bank';
    protected static ?string $pluralLabel = 'Akun Bank';
    protected static ?string $modelLabel = 'Akun Bank';
    protected static ?string $pluralModelLabel = 'Akun Bank';

    public static function form(Schema $schema): Schema
    {
        return AkunBankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AkunBanksTable::configure($table);
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
            'index' => ListAkunBanks::route('/'),
            'create' => CreateAkunBank::route('/create'),
            'edit' => EditAkunBank::route('/{record}/edit'),
        ];
    }
}
