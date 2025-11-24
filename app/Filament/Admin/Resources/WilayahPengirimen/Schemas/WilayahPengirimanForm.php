<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class WilayahPengirimanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

        Select::make('kecamatan_id')
            ->label('Kecamatan')
            ->options(function (Get $get, ?string $state) {

                // ID kecamatan yang sudah dipakai (kecuali yang sedang diedit)
                $usedIds = \App\Models\WilayahPengiriman::pluck('kecamatan_id')->toArray();

                // Jika sedang edit -> biarkan kecamatan miliknya tetap muncul
                if ($state && in_array($state, $usedIds)) {
                    $index = array_search($state, $usedIds);
                    unset($usedIds[$index]);
                }

                return \App\Models\Kecamatan::whereNotIn('kode_rajaongkir', $usedIds)
                    ->orderBy('nama')
                    ->pluck('nama', 'kode_rajaongkir');
            })
            ->searchable()
            ->required()
            ->live()
            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                if (!$state) return;

                $kecamatan = \App\Models\Kecamatan::where('kode_rajaongkir', $state)->first();

                if ($kecamatan) {
                    $set('kota_id', $kecamatan->kota_id);

                    $kota = $kecamatan->kota;

                    if ($kota) {
                        $set('provinsi_id', $kota->provinsi_id);
                        $set('nama', $kecamatan->nama . ', ' . $kota->nama);
                    }
                }
            }),

            TextInput::make('nama')
                ->label('Nama Wilayah')
                ->required()
                ->disabled() // auto generate
                ->dehydrated(true),

            TextInput::make('provinsi_id')
                ->label('Provinsi ID')
                ->disabled()
                ->dehydrated(true),

            TextInput::make('kota_id')
                ->label('Kota/Kabupaten ID')
                ->disabled()
                ->dehydrated(true),

            Toggle::make('aktif')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
