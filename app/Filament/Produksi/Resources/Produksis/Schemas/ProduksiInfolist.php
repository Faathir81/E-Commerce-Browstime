<?php

namespace App\Filament\Produksi\Resources\Produksis\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Produksi\Resources\Produksis\Schemas\ProduksiHelper;

class ProduksiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |----------------------------------------------------------------------
                | DETAIL PESANAN
                |----------------------------------------------------------------------
                */
                Section::make('Detail Pesanan')
                    ->schema([
                        TextEntry::make('kode')
                            ->label('Kode Pesanan')
                            ->copyable(),

                        TextEntry::make('nama_pelanggan')
                            ->label('Customer'),

                        TextEntry::make('created_at')
                            ->label('Tanggal')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'pending'   => 'gray',
                                'paid'      => 'info',
                                'produksi'  => 'warning',
                                'dikirim'   => 'success',
                                'selesai'   => 'success',
                                default     => 'gray',
                            }),
                    ])
                    ->columns(2),


                /*
                |----------------------------------------------------------------------
                | ALAMAT PENGIRIMAN
                |---------------------------------------------------------------------- 
                | Diambil lewat: pesanan -> pelanggan -> alamat_pengirimans (first)
                |----------------------------------------------------------------------
                */
                Section::make('Alamat Pengiriman')
                    ->schema([

                        TextEntry::make('alamat_nama_penerima')
                            ->label('Nama Penerima')
                            ->state(function ($record) {
                                return $record->resolvedPelanggan()?->alamatPengiriman->first()?->nama_penerima ?? '-';
                            }),

                        TextEntry::make('alamat_no_hp')
                            ->label('No HP')
                            ->state(function ($record) {
                                return $record->resolvedPelanggan()?->alamatPengiriman->first()?->no_hp ?? '-';
                            }),

                        TextEntry::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->state(function ($record) {
                                return $record->resolvedPelanggan()?->alamatPengiriman->first()?->alamat_lengkap ?? '-';
                            })
                            ->columnSpanFull(),

                        TextEntry::make('alamat_wilayah')
                            ->label('Wilayah (Kecamatan)')
                            ->state(function ($record) {
                                return $record->wilayahPengiriman?->nama ?? '-';
                            }),

                        TextEntry::make('ongkir')
                            ->label('Ongkir')
                            ->money('IDR'),

                    ])
                    ->columns(2),


                /*
                |----------------------------------------------------------------------
                | ITEM PESANAN
                |----------------------------------------------------------------------
                */
                Section::make('Item Pesanan')
                    ->schema([
                        RepeatableEntry::make('detailPesanans')
                            ->label('')
                            ->schema([
                                TextEntry::make('produk.nama')->label('Produk'),

                                TextEntry::make('qty')->label('Qty'),

                                TextEntry::make('harga')
                                    ->label('Harga')
                                    ->money('IDR'),

                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR'),
                            ])
                            ->columns(4),
                    ]),


                /*
                |----------------------------------------------------------------------
                | KEBUTUHAN PRODUKSI (BOM × QTY)
                |----------------------------------------------------------------------
                */
                Section::make('Kebutuhan Bahan (BOM × Qty)')
                    ->schema([
                        RepeatableEntry::make('kebutuhan_bahan')
                            ->label('')
                            ->schema([
                                TextEntry::make('nama')->label('Bahan'),

                                TextEntry::make('kebutuhan')
                                    ->label('Kebutuhan')
                                    ->formatStateUsing(fn ($state, $record) =>
                                        number_format($state, 2) . ' ' . ($record['satuan'] ?? '')
                                    ),

                                TextEntry::make('stok')->label('Stok'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => $state === 'cukup' ? 'success' : 'danger'),
                            ])
                            ->columns(4)
                            ->state(function ($record) {
                                $kebutuhan = ProduksiHelper::hitungKebutuhanBahan($record);

                                return collect($kebutuhan)->map(function ($item) {
                                    $bahan = $item['bahan'];

                                    return [
                                        'nama'      => $bahan->nama,
                                        'kebutuhan' => $item['kebutuhan'],
                                        'satuan'    => $bahan->satuan?->nama,
                                        'stok'      => $bahan->stok_virtual,
                                        'status'    => $bahan->stok_virtual >= $item['kebutuhan']
                                                        ? 'cukup'
                                                        : 'kurang',
                                    ];
                                })->values()->toArray();
                            }),
                    ]),


                /*
                |----------------------------------------------------------------------
                | ESTIMASI PRODUKSI
                |----------------------------------------------------------------------
                */
                Section::make('Estimasi Waktu Produksi')
                    ->schema([
                        TextEntry::make('estimasi_total_menit')
                            ->label('Estimasi (Menit)')
                            ->state(function ($record) {
                                $total = 0;
                                foreach ($record->detailPesanans as $item) {
                                    $estimasi = $item->produk?->waktu_produksi ?? 0;
                                    $total += $estimasi * $item->qty;
                                }
                                return $total . ' menit';
                            })
                            ->columnSpanFull(),
                    ]),


                /*
                |----------------------------------------------------------------------
                | INFORMASI PEMBAYARAN
                |---------------------------------------------------------------------- 
                | Diambil dari tabel `pembayarans`
                |----------------------------------------------------------------------
                */
                Section::make('Informasi Pembayaran')
                    ->schema([

                        TextEntry::make('pembayaran_metode')
                            ->label('Metode Pembayaran')
                            ->state(fn ($record) => $record->pembayaran?->metode ?? '-'),

                        TextEntry::make('pembayaran_status')
                            ->label('Status Pembayaran')
                            ->badge()
                            ->state(fn ($record) => $record->pembayaran?->status ?? '-')
                            ->color(fn ($state) => match ($state) {
                                'valid' => 'success',
                                'pending' => 'warning',
                                'invalid' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('pembayaran_dibayar_pada')
                            ->label('Dibayar Pada')
                            ->state(fn ($record) =>
                                $record->pembayaran?->created_at
                                    ? $record->pembayaran->created_at->format('d M Y H:i')
                                    : '-'
                            ),

                    ])
                    ->columns(3),
            ]);
    }
}
