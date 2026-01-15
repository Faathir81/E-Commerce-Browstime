<?php

namespace App\Filament\Produksi\Resources\Produksis\Schemas;

use App\Models\MutasiStok;
use App\Support\StatusStyle;
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
                Section::make('Detail Pesanan')
                    ->schema([
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
                                    ->formatStateUsing(fn (?string $state) => StatusStyle::pesanan($state)['label'])
                                    ->color(fn (?string $state) => StatusStyle::pesanan($state)['color'])
                                    ->icon(fn (?string $state) => StatusStyle::pesanan($state)['icon']),
                            ])
                            ->columns(2),

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

                        Section::make('Kebutuhan Bahan (BOM x Qty)')
                            ->schema([
                                RepeatableEntry::make('kebutuhan_bahan')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('nama')->label('Bahan'),

                                        TextEntry::make('kebutuhan')
                                            ->label('Kebutuhan')
                                            ->formatStateUsing(function ($state, $record) {
                                                if ($state === null) {
                                                    return '-';
                                                }

                                                $formatted = number_format((float) $state, 2, '.', ',');
                                                $formatted = rtrim(rtrim($formatted, '0'), '.');

                                                return $formatted . ' ' . ($record['satuan'] ?? '');
                                            }),

                                        TextEntry::make('stok')
                                            ->label('Stok')
                                            ->formatStateUsing(function ($state) {
                                                if ($state === null) {
                                                    return '-';
                                                }

                                                $formatted = number_format((float) $state, 2, '.', ',');
                                                return rtrim(rtrim($formatted, '0'), '.');
                                            }),

                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn ($state) => $state === 'cukup' ? 'success' : 'danger'),
                                    ])
                                    ->columns(4)
                                    ->state(function ($record) {
                                        $kebutuhan = ProduksiHelper::hitungKebutuhanBahan($record);
                                        $bahanIds = collect($kebutuhan)->keys();

                                        // Ambil stok awal yang dicatat saat mutasi produksi (jika sudah diproses)
                                        $stokAwalProduksi = MutasiStok::query()
                                            ->whereIn('bahan_id', $bahanIds)
                                            ->where('jenis_mutasi', 'pemakaian_produksi')
                                            ->where('catatan', 'Produksi pesanan ' . $record->kode)
                                            ->get()
                                            ->keyBy('bahan_id');

                                        return collect($kebutuhan)->map(function ($item) use ($stokAwalProduksi) {
                                            $bahan = $item['bahan'];
                                            $stokAwal = $stokAwalProduksi[$bahan->id]->stok_awal ?? $bahan->stok_virtual;

                                            return [
                                                'nama'      => $bahan->nama,
                                                'kebutuhan' => $item['kebutuhan'],
                                                'satuan'    => $bahan->satuan?->nama,
                                                'stok'      => $stokAwal,
                                                'status'    => $stokAwal >= $item['kebutuhan']
                                                                ? 'cukup'
                                                                : 'kurang',
                                            ];
                                        })->values()->toArray();
                                    }),
                            ]),

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

                        Section::make('Informasi Pembayaran')
                            ->schema([
                                TextEntry::make('pembayaran_metode')
                                    ->label('Metode Pembayaran')
                                    ->badge()
                                    ->state(fn ($record) => $record->pembayaran?->metode)
                                    ->formatStateUsing(fn (?string $state) => StatusStyle::metodePembayaran($state)['label'])
                                    ->color(fn (?string $state) => StatusStyle::metodePembayaran($state)['color'])
                                    ->icon(fn (?string $state) => StatusStyle::metodePembayaran($state)['icon']),

                                TextEntry::make('pembayaran_status')
                                    ->label('Status Pembayaran')
                                    ->badge()
                                    ->state(fn ($record) => $record->pembayaran?->status)
                                    ->formatStateUsing(fn (?string $state) => StatusStyle::pembayaran($state)['label'])
                                    ->color(fn (?string $state) => StatusStyle::pembayaran($state)['color'])
                                    ->icon(fn (?string $state) => StatusStyle::pembayaran($state)['icon']),

                                TextEntry::make('pembayaran_dibayar_pada')
                                    ->label('Dibayar Pada')
                                    ->state(fn ($record) =>
                                        $record->pembayaran?->created_at
                                            ? $record->pembayaran->created_at->format('d M Y H:i')
                                            : '-'
                                    ),

                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
