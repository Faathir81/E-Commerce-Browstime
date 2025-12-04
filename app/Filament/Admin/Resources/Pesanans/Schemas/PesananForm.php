<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use App\Models\Pesanan;
use App\Support\StatusStyle;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        $hiddenStatuses = ['produksi', 'dikirim'];
        $hiddenStatusMap = array_flip($hiddenStatuses);

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
                            ->options(array_diff_key(StatusStyle::pesananOptions(), $hiddenStatusMap))
                            ->icons(array_diff_key(StatusStyle::pesananIcons(), $hiddenStatusMap))
                            ->colors(array_diff_key(StatusStyle::pesananColors(), $hiddenStatusMap))
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

                        TextInput::make('status_saat_ini')
                            ->label('Status Saat Ini')
                            ->disabled()
                            ->dehydrated(false)
                            ->reactive()
                            ->afterStateHydrated(function ($component, Get $get) {
                                $component->state(
                                    StatusStyle::pesanan($get('status'))['label'] ?? '-',
                                );
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }
}