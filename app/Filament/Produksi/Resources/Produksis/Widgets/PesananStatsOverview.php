<?php

namespace App\Filament\Produksi\Resources\Produksis\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PesananStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Pesanan::count();
        $open = Pesanan::whereIn('status', ['pending', 'paid', 'perlu_perbaikan', 'produksi', 'dikirim'])->count();
        $avgTotal = Pesanan::avg('total') ?? 0;

        return [
            Stat::make('Pesanan', number_format($total))
                ->icon('heroicon-m-rectangle-stack')
                ->color('primary')
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
