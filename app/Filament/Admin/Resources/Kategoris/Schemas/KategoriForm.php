<?php

namespace App\Filament\Admin\Resources\Kategoris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Kategori')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                if (blank($state)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
