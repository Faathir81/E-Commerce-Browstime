<?php

namespace App\Filament\Admin\Resources\MutasiStoks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class MutasiStokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ===========================
                //   BAHAN BAKU
                // ===========================
                Select::make('bahan_id')
                    ->relationship('bahan', 'nama')
                    ->label('Bahan Baku')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $get, $set) {

                        $bahan = \App\Models\BahanBaku::find($state);
                        $stok = (float) ($bahan?->stok_awal ?? 0);

                        $set('current_stok', $stok);
                        $set('stok_awal', $stok);

                        $qty   = (float) ($get('qty') ?? 0);
                        $jenis = $get('jenis_mutasi');

                        if (!$jenis) {
                            $set('stok_akhir', $stok);
                            return;
                        }

                        if ($jenis === 'penyesuaian') {
                            $set('stok_akhir', $qty);
                        } elseif ($jenis === 'stok_masuk') {
                            $set('stok_akhir', $stok + $qty);
                        } else {
                            $set('stok_akhir', $stok - $qty);
                        }
                    }),


                // ===========================
                //   JENIS MUTASI
                // ===========================
                Select::make('jenis_mutasi')
                    ->label('Jenis Mutasi')
                    ->options([
                        'stok_masuk'   => 'Stok Masuk',
                        'stok_rusak'   => 'Stok Rusak',
                        'stok_expired' => 'Stok Expired',
                        'penyesuaian'  => 'Penyesuaian Stok',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $get, $set) {

                        $qty      = (float) ($get('qty') ?? 0);
                        $stokAwal = (float) ($get('stok_awal') ?? 0);

                        if (!$state) {
                            $set('stok_akhir', $stokAwal);
                            return;
                        }

                        if ($state === 'penyesuaian') {
                            $set('stok_akhir', $qty);
                        } elseif ($state === 'stok_masuk') {
                            $set('stok_akhir', $stokAwal + $qty);
                        } else {
                            $set('stok_akhir', $stokAwal - $qty);
                        }
                    }),


                // ===========================
                //   QTY / JUMLAH
                // ===========================
                TextInput::make('qty')
                    ->label('Jumlah')
                    ->numeric()
                    ->live()
                    ->suffix(fn (Get $get) =>
                        optional(\App\Models\BahanBaku::find($get('bahan_id')))
                            ->satuan?->nama ?? ''
                    )
                    ->afterStateUpdated(function ($state, $get, $set) {

                        $jenis    = $get('jenis_mutasi');
                        $stokAwal = (float) ($get('stok_awal') ?? 0);
                        $qty      = (float) ($state ?? 0);

                        if (!$jenis) {
                            $set('stok_akhir', $stokAwal);
                            return;
                        }

                        if ($jenis === 'penyesuaian') {
                            $set('stok_akhir', $qty);
                        } elseif ($jenis === 'stok_masuk') {
                            $set('stok_akhir', $stokAwal + $qty);
                        } else {
                            $set('stok_akhir', $stokAwal - $qty);
                        }
                    }),


                // ===========================
                //   STOK SAAT INI (READONLY)
                // ===========================
                TextInput::make('current_stok')
                    ->label('Stok Saat Ini')
                    ->disabled()
                    ->dehydrated(true)
                    ->suffix(fn (Get $get) =>
                        optional(\App\Models\BahanBaku::find($get('bahan_id')))
                            ->satuan?->nama ?? ''
                    ),


                // ===========================
                //   STOK AWAL
                // ===========================
                TextInput::make('stok_awal')
                    ->disabled()
                    ->dehydrated(true)
                    ->default(fn (Get $get) =>
                        optional(\App\Models\BahanBaku::find($get('bahan_id')))->stok_awal ?? 0
                    )
                    ->suffix(fn (Get $get) =>
                        optional(\App\Models\BahanBaku::find($get('bahan_id')))
                            ->satuan?->nama ?? ''
                    ),


                // ===========================
                //   STOK AKHIR
                // ===========================
                TextInput::make('stok_akhir')
                    ->disabled()
                    ->dehydrated(true)
                    ->suffix(fn (Get $get) =>
                        optional(\App\Models\BahanBaku::find($get('bahan_id')))
                            ->satuan?->nama ?? ''
                    ),


                Textarea::make('catatan')
                    ->label('Catatan')
                    ->columnSpanFull(),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),
            ]);
    }
}
