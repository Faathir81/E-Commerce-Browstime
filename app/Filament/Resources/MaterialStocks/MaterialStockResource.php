<?php

namespace App\Filament\Resources\MaterialStocks;

use App\Filament\Resources\MaterialStocks\Pages\CreateMaterialStock;
use App\Filament\Resources\MaterialStocks\Pages\EditMaterialStock;
use App\Filament\Resources\MaterialStocks\Pages\ListMaterialStocks;
use App\Filament\Resources\MaterialStocks\Schemas\MaterialStockForm;
use App\Filament\Resources\MaterialStocks\Tables\MaterialStocksTable;
use App\Models\MaterialStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class MaterialStockResource extends Resource
{
    protected static ?string $model = MaterialStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('material_id')
                ->label('Material')
                ->relationship('material', 'name')
                ->required(),
            Select::make('type')
                ->label('Type')
                ->options([
                    'in' => 'In',
                    'out' => 'Out',
                ])
                ->required(),
            TextInput::make('qty')
                ->label('Quantity')
                ->required(),
            Textarea::make('note')
                ->label('Note')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('material.name')
                ->label('Material')
                ->sortable(),
            TextColumn::make('type')
                ->label('Type')
                ->sortable(),
            TextColumn::make('qty')
                ->label('Quantity')
                ->sortable(),
            TextColumn::make('note')
                ->label('Note')
                ->sortable(),
        ]);
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
            'index' => ListMaterialStocks::route('/'),
            'create' => CreateMaterialStock::route('/create'),
            'edit' => EditMaterialStock::route('/{record}/edit'),
        ];
    }
}
