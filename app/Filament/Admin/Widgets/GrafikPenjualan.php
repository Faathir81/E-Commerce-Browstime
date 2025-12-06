<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class GrafikPenjualan extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan (7 Hari Terakhir)';
    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $endDate = Carbon::now()->startOfDay();
        $startDate = $endDate->copy()->subDays(6);

        $rawData = Pesanan::query()
            ->whereIn('pesanans.status', ['paid', 'produksi', 'dikirim', 'selesai'])
            ->whereBetween('pesanans.created_at', [$startDate, $endDate->copy()->endOfDay()])
            ->selectRaw('DATE(pesanans.created_at) as tanggal, SUM(pesanans.total) as omzet')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('omzet', 'tanggal');

        $labels = [];
        $dataset = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $date;
            $dataset[] = (float) ($rawData[$date] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet',
                    'data' => $dataset,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
