<?php

namespace App\Filament\Produksi\Resources\Produksis\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PesananStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $days = collect(range(6, 0))->map(fn (int $daysAgo) => Carbon::now()->subDays($daysAgo));
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
        $paidTrend = $days->map(function (Carbon $date) {
            return (int) Pesanan::query()
                ->where('status', Pesanan::STATUS_PAID)
                ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->count();
        })->all();
        $openTrend = $days->map(function (Carbon $date) use ($openStatuses) {
            return (int) Pesanan::query()
                ->whereIn('status', $openStatuses)
                ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->count();
        })->all();
        $avgTrend = $days->map(function (Carbon $date) use ($revenueStatuses) {
            return (float) (Pesanan::query()
                ->whereIn('status', $revenueStatuses)
                ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->avg('total') ?? 0);
        })->all();

        return [
            Stat::make('Pesanan Terbayar', number_format($paid))
                ->icon('heroicon-m-rectangle-stack')
                ->color('warning')
                ->chart($paidTrend),

            Stat::make('Pesanan terbuka', number_format($open))
                ->icon('heroicon-m-clock')
                ->color('info')
                ->chart($openTrend),

            Stat::make('Rata-rata harga', number_format($avgTotal, 0, ',', '.'))
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($avgTrend),
        ];
    }
}
