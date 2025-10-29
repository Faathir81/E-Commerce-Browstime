<?php

namespace App\Filament\Resources\Categories\Categories\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Category')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->maxLength(100)
                        ->required()
                        ->unique('categories', 'name', ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug((string) $state))),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(120)
                        ->required()
                        ->unique('categories', 'slug', ignoreRecord: true)
                        ->disabled(fn ($record) => filled($record)), // disable saat edit
                ])
                ->columns(2),
        ]);
    }
}
