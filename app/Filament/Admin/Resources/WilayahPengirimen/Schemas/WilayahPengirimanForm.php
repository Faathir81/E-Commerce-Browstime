<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class WilayahPengirimanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('kota_id')
                ->label('Kota/Kabupaten')
                ->options(fn () => \App\Models\Kota::orderBy('nama')->pluck('nama', 'id'))
                ->searchable()
                ->required()
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                    if (! $state) {
                        return;
                    }

                    $kota = \App\Models\Kota::find($state);
                    $provinsi = $kota?->provinsi;

                    if ($kota) {
                        $set('provinsi_id', $provinsi?->id);
                        $set('kota_label', $kota->nama);
                        $set('provinsi_label', $provinsi?->nama);
                        $set('nama', $kota->nama);
                    }
                }),

            Hidden::make('nama')
                ->required()
                ->dehydrated(true),

            Hidden::make('provinsi_id'),

            Hidden::make('kecamatan_id')
                ->default(0),

            TextInput::make('provinsi_label')
                ->label('Provinsi')
                ->disabled()
                ->dehydrated(false)
                ->afterStateHydrated(function (Set $set, Get $get) {
                    if ($id = $get('provinsi_id')) {
                        $set('provinsi_label', optional(\App\Models\Provinsi::find($id))->nama);
                    }
                }),

            Toggle::make('aktif')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
