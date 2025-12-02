<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use App\Models\Pesanan;
use App\Support\StatusStyle;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Section;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('nama_pelanggan')
                            ->label('Nama Pelanggan')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, ?Pesanan $record): void {
                                $component->state($record?->nama_pelanggan);
                            })
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Pesanan')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\ToggleButtons::make('status')
                            ->label('Status')
                            ->options(StatusStyle::pesananOptions())
                            ->icons(StatusStyle::pesananIcons())
                            ->colors(StatusStyle::pesananColors())
                            ->columnSpanFull()
                            ->inline()
                            ->required(),

                        Forms\Components\TextInput::make('no_resi')
                            ->label('Nomor Resi')
                            ->columnSpan(1),

                        Forms\Components\DateTimePicker::make('eta')
                            ->label('Estimasi Sampai')
                            ->seconds(false)
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }
}
