<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('unit')
                    ->label('Unit')
                    ->required(),
            ]);
    }
}
