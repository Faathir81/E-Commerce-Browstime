<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PenjualanHarianOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $totalHariIni = Pesanan::query()
            ->whereIn('pesanans.status', ['paid', 'produksi', 'dikirim', 'selesai'])
            ->whereBetween('pesanans.created_at', [$todayStart, $todayEnd])
            ->sum('pesanans.total');

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($totalHariIni, 0, ',', '.'))
                ->description('Total omzet masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
