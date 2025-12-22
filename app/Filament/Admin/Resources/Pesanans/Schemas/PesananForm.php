<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use App\Models\Pesanan;
use App\Support\OrderSuccessHelper;
use App\Support\StatusStyle;
use Illuminate\Validation\Rule;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        // Admin hanya mengelola pembayaran & pengiriman, tidak boleh menyelesaikan atau membatalkan
        $allowedAdminStatuses = ['pending', 'perlu_perbaikan', 'paid', 'dikirim'];
        $allowedStatusMap = array_flip($allowedAdminStatuses);

        $statusOptions = array_intersect_key(StatusStyle::pesananOptions(), $allowedStatusMap);
        $statusIcons = array_intersect_key(StatusStyle::pesananIcons(), $allowedStatusMap);
        $statusColors = array_intersect_key(StatusStyle::pesananColors(), $allowedStatusMap);

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
                            ->options($statusOptions)
                            ->icons($statusIcons)
                            ->colors($statusColors)
                            ->afterStateHydrated(function ($component, $state, ?Pesanan $record) use ($statusOptions) {
                                $currentStatus = $record?->status ?? $state;
                                $component->state(array_key_exists($currentStatus, $statusOptions) ? $currentStatus : null);
                            })
                            ->dehydrateStateUsing(fn ($state, ?Pesanan $record) => $state ?? ($record?->status ?? null))
                            ->required(function (?Pesanan $record) use ($statusOptions) {
                                if (! $record) {
                                    return true;
                                }

                                return array_key_exists($record->status, $statusOptions);
                            })
                            ->rule(fn () => Rule::in(Pesanan::STATUSES))
                            ->columnSpanFull()
                            ->inline()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('status_saat_ini', StatusStyle::pesanan($state)['label'] ?? '-');
                            }),

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
                            ->afterStateHydrated(function ($component, $state, ?Pesanan $record): void {
                                $component->state(StatusStyle::pesanan($record?->status ?? $state)['label'] ?? '-');
                            })
                            ->columnSpan(1),

                        Section::make('Alamat Pengiriman')
                            ->columnSpanFull()
                            ->schema([
                        Textarea::make('alamat_pengiriman')
                            ->hiddenLabel()
                                    ->rows(3)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(function (Textarea $component, $state, ?Pesanan $record): void {
                                        if (! $record) {
                                            $component->state('-');
                                            return;
                                        }

                                        $pelanggan = $record->resolvedPelanggan();
                                        $alamatPengiriman = $pelanggan?->alamatPengiriman()->latest()->first();

                                        $component->state(
                                            OrderSuccessHelper::formatFullAddress($record, $pelanggan, $alamatPengiriman) ?: '-'
                                        );
                                    }),
                            ]),
                    ])
                    ->columns(3),


            ]);
    }
}
