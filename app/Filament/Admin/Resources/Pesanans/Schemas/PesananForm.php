<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Section;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Pesanan')
                            ->disabled(),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Status & Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending'   => 'Menunggu Pembayaran',
                                'paid'      => 'Terbayar',
                                'produksi'  => 'Diproduksi',
                                'dikirim'   => 'Dikirim',
                                'selesai'   => 'Selesai',
                                'batal'     => 'Batal',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\TextInput::make('no_resi')
                            ->label('Nomor Resi'),

                        Forms\Components\DateTimePicker::make('eta')
                            ->label('Estimasi Sampai')
                            ->seconds(false),
                    ])
                    ->columns(3),
            ]);
    }
}
