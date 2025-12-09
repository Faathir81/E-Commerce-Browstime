<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen\Schemas;

use Filament\Forms\Components\Hidden;
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
                        $kota = $kecamatan->kota;
                        $provinsi = $kota?->provinsi;

                        $set('kota_id', $kota?->id);
                        $set('provinsi_id', $provinsi?->id);

                        $set('kota_label', $kota?->nama);
                        $set('provinsi_label', $provinsi?->nama);
                        $set('nama', $kecamatan->nama . ', ' . ($kota->nama ?? ''));
                    }
                }),

            TextInput::make('nama')
                ->label('Nama Wilayah')
                ->required()
                ->disabled() // auto generate
                ->dehydrated(true),

            Hidden::make('provinsi_id'),

            Hidden::make('kota_id'),

            TextInput::make('provinsi_label')
                ->label('Provinsi')
                ->disabled()
                ->dehydrated(false)
                ->afterStateHydrated(function (Set $set, Get $get) {
                    if ($id = $get('provinsi_id')) {
                        $set('provinsi_label', optional(\App\Models\Provinsi::find($id))->nama);
                    }
                }),

            TextInput::make('kota_label')
                ->label('Kota/Kabupaten')
                ->disabled()
                ->dehydrated(false)
                ->afterStateHydrated(function (Set $set, Get $get) {
                    if ($id = $get('kota_id')) {
                        $set('kota_label', optional(\App\Models\Kota::find($id))->nama);
                    }
                }),

            Toggle::make('aktif')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
