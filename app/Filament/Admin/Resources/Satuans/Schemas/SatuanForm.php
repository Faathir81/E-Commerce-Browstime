<?php

namespace App\Filament\Admin\Resources\Satuans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SatuanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Satuan')
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Satuan')
                        ->required(),

                    TextInput::make('symbol')
                        ->label('Simbol (gr/ml/pcs)')
                        ->maxLength(10),
                ])
                ->columns(2),
        ]);
    }
}