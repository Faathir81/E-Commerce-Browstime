<?php

namespace App\Filament\Admin\Resources\AkunBanks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AkunBankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_bank')
                ->label('Nama Bank')
                ->required(),

            TextInput::make('nama_pemilik')
                ->label('Nama Pemilik')
                ->required(),

            TextInput::make('nomor_rekening')
                ->label('Nomor Rekening')
                ->required(),

            Toggle::make('aktif')
                ->label('Aktif')
                ->default(true),

            TextInput::make('urutan')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ]);
    }
}
