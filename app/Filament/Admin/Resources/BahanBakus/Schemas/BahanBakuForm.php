<?php

namespace App\Filament\Admin\Resources\BahanBakus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BahanBakuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Bahan Baku')
                ->schema([

                    TextInput::make('nama')
                        ->label('Nama Bahan')
                        ->required(),

                    Select::make('satuan_id')
                        ->label('Satuan')
                        ->relationship('satuan', 'nama')
                        ->required(),

                    TextInput::make('stok_awal')
                        ->label('Stok Awal')
                        ->numeric()
                        ->default(0),

                    TextInput::make('stok_minimum')
                        ->label('Stok Minimum')
                        ->numeric()
                        ->default(0),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->columnSpanFull(),

                ])->columns(2),
        ]);
    }
}
