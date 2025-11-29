<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PenjualanHarianOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalHariIni = Pesanan::query()
            ->leftJoin('pembayarans as pay', function ($join) {
                $join->on('pay.pesanan_id', '=', 'pesanans.id')
                    ->where('pay.status', 'valid');
            })
            ->whereIn('pesanans.status', ['paid', 'dikirim'])
            ->whereDate(DB::raw('COALESCE(pay.created_at, pesanans.updated_at)'), today())
            ->sum('pesanans.total');

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($totalHariIni, 0, ',', '.'))
                ->description('Total omzet masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
