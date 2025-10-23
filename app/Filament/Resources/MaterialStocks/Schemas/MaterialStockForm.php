<?php

namespace App\Filament\Resources\MaterialStocks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class MaterialStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
}
