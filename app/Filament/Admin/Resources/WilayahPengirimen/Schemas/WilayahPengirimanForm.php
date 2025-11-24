<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class WilayahPengirimanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Wilayah')
                    ->required(),

                TextInput::make('provinsi_id')
                    ->label('Provinsi ID')
                    ->numeric()
                    ->required(),

                TextInput::make('kota_id')
                    ->label('Kota/Kabupaten ID')
                    ->numeric()
                    ->required(),

                TextInput::make('kecamatan_id')
                    ->label('Kecamatan ID (RajaOngkir)')
                    ->numeric()
                    ->required(),

                Toggle::make('aktif')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
