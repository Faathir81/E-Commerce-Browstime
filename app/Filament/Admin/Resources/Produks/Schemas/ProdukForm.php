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
                        })
                        ->columnSpan(6),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->disabled()
                        ->dehydrated(true)
                        ->columnSpan(6),

                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->relationship('kategori', 'nama')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('harga')
                        ->label('Harga')
                        ->numeric()
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('waktu_produksi')
                        ->label('Waktu Produksi (menit)')
                        ->numeric()
                        ->required()
                        ->columnSpan(4),

                    TextInput::make('berat')
                        ->label('Berat (gram)')
                        ->numeric()
                        ->minValue(1)
                        ->suffix('gr')
                        ->helperText('Masukkan berat bersih dalam gram')
                        ->required()
                        ->columnSpan(4),

                    FileUpload::make('gambar')
                        ->label('Gambar Produk')
                        ->image()
                        ->disk('public')
                        ->directory('produk')
                        ->visibility('public')
                        ->required()
                        ->columnSpan(4),

                    Toggle::make('is_active')
                        ->label('Aktif di Katalog')
                        ->default(true)
                        ->columnSpan(4),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->columnSpanFull(),

                ])
                ->columnSpanFull(),

        ]);
    }
}
