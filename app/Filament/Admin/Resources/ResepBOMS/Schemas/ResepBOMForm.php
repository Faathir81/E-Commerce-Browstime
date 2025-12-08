<?php

namespace App\Filament\Admin\Resources\ResepBOMS\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class ResepBOMForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                // PILIH PRODUK
                Select::make('produk_id')
                    ->relationship('produk', 'nama')
                    ->label('Produk')
                    ->preload()   // show all product names immediately
                    ->required(),

                // NESTED DETAIL RESEP
                Repeater::make('detail')
                    ->relationship()
                    ->label('Detail Bahan')
                    ->columns(3)
                    ->schema([

                    Select::make('bahan_id')
                        ->relationship('bahan', 'nama')
                        ->label('Bahan')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $bahan = \App\Models\BahanBaku::find($state);
                            if ($bahan) {
                                $set('satuan_id', $bahan->satuan_id);  // autofill
                            }
                        })
                        ->required(),

                    TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->numeric()
                        ->required(),

                    Select::make('satuan_id')
                        ->relationship('satuan', 'nama')
                        ->label('Satuan')
                        ->disabled()      // 🔒 prevent human error
                        ->dehydrated()    // tetap tersimpan di DB
                        ->required(),
                    ])
                    ->addActionLabel('Tambah Bahan'),
            ]);
    }
}
