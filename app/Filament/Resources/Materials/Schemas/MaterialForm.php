<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Material Name')
                ->required()
                ->maxLength(120),

            TextInput::make('unit')
                ->label('Unit (kg, g, ml, pcs, dll)')
                ->required()
                ->maxLength(20),

            TextInput::make('min_qty')
                ->label('Minimum Stock')
                ->numeric()
                ->default(0)
                ->required(),
        ])->columns(3);
    }
}
