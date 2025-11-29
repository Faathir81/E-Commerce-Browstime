<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PesananHarianOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $jumlahPesanan = Pesanan::whereDate('created_at', today())->count();

        return [
            Stat::make('Pesanan Hari Ini', $jumlahPesanan)
                ->description('Jumlah order yang masuk hari ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
        ];
    }
}
