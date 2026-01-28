<?php

namespace App\Filament\Admin\Resources\Pesanans\Schemas;

use App\Models\Pesanan;
use App\Support\OrderSuccessHelper;
use App\Support\StatusStyle;
use Illuminate\Validation\Rule;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
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

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn (?Pesanan $record) => self::nextStatusOptions($record?->status))
                            ->native(false)
                            ->helperText('Status produksi diatur di panel produksi. Admin: Pending → Terbayar → (Perlu Perbaikan bila perlu) → Dikirim (setelah Produksi).')
                            ->disabled(fn (?Pesanan $record, $state) => ($record?->status ?? $state) === Pesanan::STATUS_SELESAI)
                            ->afterStateHydrated(function ($component, $state, ?Pesanan $record): void {
                                $current = $record?->status ?? $state;
                                $options = self::nextStatusOptions($current);
                                $component->state(array_key_exists($current, $options) ? $current : null);
                            })
                            ->dehydrateStateUsing(fn ($state, ?Pesanan $record) => $state ?? ($record?->status ?? null))
                            ->required()
                            ->rule(fn () => Rule::in(Pesanan::STATUSES))
                            ->columnSpanFull()
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

    public static function nextStatusOptions(?string $current): array
    {
        // Admin hanya kelola Pending, Terbayar, Perlu Perbaikan, Dikirim.
        $map = [
            Pesanan::STATUS_PENDING => [
                Pesanan::STATUS_PENDING => StatusStyle::pesanan(Pesanan::STATUS_PENDING)['label'],
                Pesanan::STATUS_PAID => StatusStyle::pesanan(Pesanan::STATUS_PAID)['label'],
                Pesanan::STATUS_PERLU_PERBAIKAN => StatusStyle::pesanan(Pesanan::STATUS_PERLU_PERBAIKAN)['label'],
            ],
            Pesanan::STATUS_PAID => [
                Pesanan::STATUS_PAID => StatusStyle::pesanan(Pesanan::STATUS_PAID)['label'],
                Pesanan::STATUS_PERLU_PERBAIKAN => StatusStyle::pesanan(Pesanan::STATUS_PERLU_PERBAIKAN)['label'],
            ],
            Pesanan::STATUS_PERLU_PERBAIKAN => [
                Pesanan::STATUS_PERLU_PERBAIKAN => StatusStyle::pesanan(Pesanan::STATUS_PERLU_PERBAIKAN)['label'],
                Pesanan::STATUS_PAID => StatusStyle::pesanan(Pesanan::STATUS_PAID)['label'],
            ],
            Pesanan::STATUS_PRODUKSI => [
                Pesanan::STATUS_PRODUKSI => StatusStyle::pesanan(Pesanan::STATUS_PRODUKSI)['label'],
                Pesanan::STATUS_DIKIRIM => StatusStyle::pesanan(Pesanan::STATUS_DIKIRIM)['label'],
            ],
            Pesanan::STATUS_DIKIRIM => [
                Pesanan::STATUS_DIKIRIM => StatusStyle::pesanan(Pesanan::STATUS_DIKIRIM)['label'],
            ],
            Pesanan::STATUS_BATAL => [
                Pesanan::STATUS_BATAL => StatusStyle::pesanan(Pesanan::STATUS_BATAL)['label'],
            ],
        ];

        $key = $current ?? Pesanan::STATUS_PENDING;

        return $map[$key] ?? $map[Pesanan::STATUS_PENDING];
    }
}
