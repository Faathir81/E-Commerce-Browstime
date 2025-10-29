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
                ->minValue(0)
                ->default(0)
                ->suffix(fn ($get) => $get('unit'))
                ->helperText('Isi sesuai satuan di kolom Unit, tanpa konversi otomatis (contoh: 0.5 kg = 0.5).')
                ->required(),
        ])->columns(3);
    }
}
