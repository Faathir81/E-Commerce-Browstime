<?php

namespace App\Filament\Admin\Widgets;

use App\Models\BahanBaku;
use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

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
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();
        $days = collect(range(6, 0))->map(fn (int $daysAgo) => Carbon::now()->subDays($daysAgo));

        $omzetQuery = Pesanan::query()
            ->whereIn('pesanans.status', ['paid', 'produksi', 'dikirim', 'selesai'])
            ->whereBetween('pesanans.created_at', [$todayStart, $todayEnd]);

        $totalHariIni = (clone $omzetQuery)->sum('pesanans.subtotal');

        $jumlahPesanan = (clone $omzetQuery)->count();

        $jumlahStokRendah = BahanBaku::whereColumn('stok_awal', '<', 'stok_minimum')->count();
        $omzetTrend = $days->map(function (Carbon $date) {
            return (int) Pesanan::query()
                ->whereIn('pesanans.status', ['paid', 'produksi', 'dikirim', 'selesai'])
                ->whereBetween('pesanans.created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->sum('pesanans.subtotal');
        })->all();

        $pesananTrend = $days->map(function (Carbon $date) {
            return (int) Pesanan::query()
                ->whereIn('pesanans.status', ['paid', 'produksi', 'dikirim', 'selesai'])
                ->whereBetween('pesanans.created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->count();
        })->all();

        $stokRendahTrend = array_fill(0, count($omzetTrend), $jumlahStokRendah);

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($totalHariIni, 0, ',', '.'))
                ->description('Total omzet masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($omzetTrend),
            Stat::make('Pesanan Hari Ini', $jumlahPesanan)
                ->description('Jumlah order yang masuk hari ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->chart($pesananTrend),
            Stat::make('Bahan Stok Rendah', $jumlahStokRendah)
                ->description('Jumlah bahan yang perlu restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart($stokRendahTrend),
        ];
    }
}
