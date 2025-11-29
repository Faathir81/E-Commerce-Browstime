<?php

namespace App\Filament\Admin\Widgets;

use App\Models\BahanBaku;
use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RingkasanStatistik extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    protected function getStats(): array
    {
        $omzetQuery = Pesanan::query()
            ->leftJoin('pembayarans as pay', function ($join) {
                $join->on('pay.pesanan_id', '=', 'pesanans.id')
                    ->where('pay.status', 'valid');
            })
            ->whereIn('pesanans.status', ['paid', 'dikirim'])
            ->whereDate(DB::raw('COALESCE(pay.created_at, pesanans.updated_at)'), today());

        $totalHariIni = (clone $omzetQuery)->sum('pesanans.total');

        $jumlahPesanan = (clone $omzetQuery)->count();

        $jumlahStokRendah = BahanBaku::whereColumn('stok_awal', '<', 'stok_minimum')->count();

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($totalHariIni, 0, ',', '.'))
                ->description('Total omzet masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Pesanan Hari Ini', $jumlahPesanan)
                ->description('Jumlah order yang masuk hari ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Bahan Stok Rendah', $jumlahStokRendah)
                ->description('Jumlah bahan yang perlu restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
