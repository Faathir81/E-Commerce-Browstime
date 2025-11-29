<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PenjualanHarianOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalHariIni = Pesanan::where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($totalHariIni, 0, ',', '.'))
                ->description('Total omzet masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
