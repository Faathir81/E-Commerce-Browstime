<?php

namespace App\Filament\Admin\Widgets;

use App\Models\BahanBaku;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StokRendahOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $jumlahStokRendah = BahanBaku::whereColumn('stok_awal', '<', 'stok_minimum')->count();

        return [
            Stat::make('Bahan Stok Rendah', $jumlahStokRendah)
                ->description('Jumlah bahan yang perlu restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
