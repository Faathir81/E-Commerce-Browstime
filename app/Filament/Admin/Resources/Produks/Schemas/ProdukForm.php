<?php

namespace App\Filament\Admin\Resources\Produks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Produk')
                ->schema([

                    TextInput::make('nama')
                        ->label('Nama Produk')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (?string $state, callable $set) {
                            if (blank($state)) return;
                            $set('slug', Str::slug($state));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->disabled()
                        ->dehydrated(true),

                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->relationship('kategori', 'nama')
                        ->required(),

                    TextInput::make('harga')
                        ->label('Harga')
                        ->numeric()
                        ->required(),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->columnSpanFull(),

                ])
                ->columns(2),

            Section::make('Produksi & Gambar')
                ->schema([

                    TextInput::make('waktu_produksi')
                        ->label('Waktu Produksi (menit)')
                        ->numeric()
                        ->required(),

                    FileUpload::make('gambar')
                        ->label('Gambar Produk')
                        ->image()
                        ->directory('produk')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Aktif di Katalog')
                        ->default(true),

                ])
                ->columns(3),

        ]);
    }
}
