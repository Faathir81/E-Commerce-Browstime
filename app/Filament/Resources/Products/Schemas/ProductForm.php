<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section; 
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Log;



class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Product Info')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(120),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(140)
                        ->helperText('Opsional, bisa dikosongkan.'),

                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    TextInput::make('price')
                        ->label('Price (Rp)')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Images')
                ->description('Upload beberapa gambar, tandai salah satu sebagai cover.')
                ->schema([
                    Repeater::make('images')
                        ->relationship()
                        ->schema([
                            Hidden::make('id'),

                            FileUpload::make('url')
                                ->label('Image')
                                ->image()
                                ->disk('public')
                                ->directory('products')

                                // item BARU (belum punya id) wajib upload
                                ->required(fn ($get) => blank($get('id')))

                                // pastikan state yg disimpan = path file
                                ->saveUploadedFileUsing(function ($file) {
                                    $name = Str::uuid() . '.' . $file->getClientOriginalExtension();

                                    // 1️⃣ Simpan file dan ambil path-nya
                                    $stored = $file->storeAs('products', $name, 'public');

                                    // 3️⃣ Return path itu biar Filament isi ke kolom `url`
                                    return $stored;
                                })

                                ->preserveFilenames(false),

                            Toggle::make('is_cover')->label('Set as cover'),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->addActionLabel('Add Image'),
                    ]),

            Section::make('Recipe / BOM')
                ->description('Bahan & takaran untuk membuat 1 unit produk.')
                ->schema([
                    Repeater::make('recipes')         // -> hasMany(ProductRecipe)
                        ->relationship()
                        ->addActionLabel('Add Ingredient')
                        ->schema([
                            Select::make('material_id')
                                ->label('Material')
                                ->relationship('material', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('qty_per_unit')
                                ->label('Qty per unit')
                                ->numeric()
                                ->minValue(0)
                                ->step('0.001')
                                ->required(),
                        ])
                        ->reorderable(false)
                        ->defaultItems(0),
                ]),
        ]);
    }
}
