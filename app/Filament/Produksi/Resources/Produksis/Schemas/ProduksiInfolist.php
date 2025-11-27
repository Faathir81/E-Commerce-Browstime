<?php

namespace App\Filament\Produksi\Resources\Produksis\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\IconEntry;
use App\Filament\Produksi\Resources\Produksis\Schemas\ProduksiHelper;

class ProduksiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | DETAIL PESANAN + CUSTOMER
                |--------------------------------------------------------------------------
                */
                Section::make('Detail Pesanan')
                    ->schema([
                        TextEntry::make('kode')
                            ->label('Kode Pesanan')
                            ->copyable(),

                        TextEntry::make('user.name')
                            ->label('Customer')
                            ->placeholder('-'),

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
                |--------------------------------------------------------------------------
                | ALAMAT PENGIRIMAN
                |--------------------------------------------------------------------------
                */
                Section::make('Alamat Pengiriman')
                    ->schema([

                        TextEntry::make('alamatPengiriman.nama_penerima')
                            ->label('Nama Penerima')
                            ->placeholder('-'),

                        TextEntry::make('alamatPengiriman.no_hp')
                            ->label('No HP')
                            ->placeholder('-'),

                        TextEntry::make('alamatPengiriman.alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),

                        TextEntry::make('alamatPengiriman.wilayah.nama')
                            ->label('Wilayah (Kecamatan)')
                            ->placeholder('-'),

                        TextEntry::make('ongkir')
                            ->label('Ongkir')
                            ->money('IDR'),
                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | CATATAN PESANAN
                |--------------------------------------------------------------------------
                */
                Section::make('Catatan Pesanan')
                    ->schema([
                        TextEntry::make('catatan')
                            ->label('Catatan dari Customer')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),


                /*
                |--------------------------------------------------------------------------
                | ITEM PESANAN
                |--------------------------------------------------------------------------
                */
                Section::make('Item Pesanan')
                    ->schema([
                        RepeatableEntry::make('detailPesanans')
                            ->label('')
                            ->schema([
                                TextEntry::make('produk.nama')
                                    ->label('Produk'),

                                TextEntry::make('qty')
                                    ->label('Qty'),

                                TextEntry::make('produk.harga')
                                    ->label('Harga')
                                    ->money('IDR'),

                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->state(fn ($record) =>
                                        ($record?->qty ?? 0) * ($record?->produk?->harga ?? 0)
                                    )
                                    ->money('IDR'),
                            ])
                            ->columns(4),
                    ]),


                /*
                |--------------------------------------------------------------------------
                | KEBUTUHAN BAHAN (BOM × QTY)
                |--------------------------------------------------------------------------
                */
                Section::make('Kebutuhan Bahan (BOM × Qty)')
                    ->schema([
                        RepeatableEntry::make('kebutuhan_bahan')
                            ->label('')
                            ->schema([
                                TextEntry::make('nama')
                                    ->label('Bahan'),

                                TextEntry::make('kebutuhan')
                                    ->label('Kebutuhan')
                                    ->formatStateUsing(fn ($state, $record) =>
                                        number_format($state, 2) . ' ' . ($record['satuan'] ?? '')
                                    ),

                                TextEntry::make('stok')
                                    ->label('Stok'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'cukup' => 'success',
                                        'kurang' => 'danger',
                                        default => 'gray',
                                    }),
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
                                        'stok'      => $bahan->stok,
                                        'status'    => $bahan->stok >= $item['kebutuhan']
                                                            ? 'cukup'
                                                            : 'kurang',
                                    ];
                                })->values()->toArray();
                            }),
                    ]),


                /*
                |--------------------------------------------------------------------------
                | ESTIMASI WAKTU PRODUKSI
                |--------------------------------------------------------------------------
                */
                Section::make('Estimasi Waktu Produksi')
                    ->schema([
                        TextEntry::make('detailPesanans')
                            ->label('Estimasi (Menit)')
                            ->state(function ($record) {
                                // total estimasi = sum(product.estimasi_produksi * qty)
                                $total = 0;

                                foreach ($record->detailPesanans as $item) {
                                    $estimasi = $item->produk?->estimasi ?? 0;  
                                    $total += $estimasi * $item->qty;
                                }

                                return $total . ' menit';
                            })
                            ->columnSpanFull(),
                    ]),


                /*
                |--------------------------------------------------------------------------
                | INFORMASI PEMBAYARAN (MANUAL / MIDTRANS)
                |--------------------------------------------------------------------------
                */
                Section::make('Informasi Pembayaran')
                    ->schema([
                        TextEntry::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->placeholder('-'),

                        TextEntry::make('payment_status')
                            ->label('Status Pembayaran')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('paid_at')
                            ->label('Dibayar Pada')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(3),


                /*
                |--------------------------------------------------------------------------
                | TIMELINE STATUS PESANAN
                |--------------------------------------------------------------------------
                */
                Section::make('Timeline Pesanan')
                    ->schema([
                        RepeatableEntry::make('timeline')
                            ->schema([
                                TextEntry::make('label')->label('Status'),
                                TextEntry::make('time')->label('Waktu'),
                            ])
                            ->state(function ($record) {
                                return [
                                    [
                                        'label' => 'Dibuat',
                                        'time'  => $record->created_at?->format('d M Y H:i'),
                                    ],
                                    [
                                        'label' => 'Dibayar',
                                        'time'  => $record->paid_at?->format('d M Y H:i') ?? '-',
                                    ],
                                    [
                                        'label' => 'Produksi',
                                        'time'  => $record->status === 'produksi'
                                            ? now()->format('d M Y H:i')
                                            : '-',
                                    ],
                                    [
                                        'label' => 'Dikirim',
                                        'time'  => $record->status === 'dikirim'
                                            ? now()->format('d M Y H:i')
                                            : '-',
                                    ],
                                ];
                            })
                    ]),
            ]);
    }
}
