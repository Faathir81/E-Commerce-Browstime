<?php

namespace App\Filament\Produksi\Resources\Produksis\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PesananStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $openStatuses = array_diff(
            Pesanan::STATUSES,
            [Pesanan::STATUS_SELESAI, Pesanan::STATUS_BATAL]
        );

        $revenueStatuses = [
            Pesanan::STATUS_PAID,
            Pesanan::STATUS_PRODUKSI,
            Pesanan::STATUS_DIKIRIM,
            Pesanan::STATUS_SELESAI,
        ];

        $paid = Pesanan::where('status', Pesanan::STATUS_PAID)->count();
        $open = Pesanan::whereIn('status', $openStatuses)->count();
        $avgTotal = Pesanan::whereIn('status', $revenueStatuses)->avg('total') ?? 0;

        return [
            Stat::make('Pesanan Terbayar', number_format($paid))
                ->icon('heroicon-m-rectangle-stack')
                ->color('warning')
                ->chart($this->emptySparkline()),

            Stat::make('Pesanan terbuka', number_format($open))
                ->icon('heroicon-m-clock')
                ->color('info')
                ->chart($this->emptySparkline()),

            Stat::make('Rata-rata harga', number_format($avgTotal, 0, ',', '.'))
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($this->emptySparkline()),
        ];
    }

    private function emptySparkline(): array
    {
        return [2, 3, 2, 4, 3, 4, 3];
    }
}
