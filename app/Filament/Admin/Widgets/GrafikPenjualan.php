<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;

class GrafikPenjualan extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan (7 Hari Terakhir)';
    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        // Ambil data 7 hari terakhir
        $data = Pesanan::where('status', 'paid')
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as omzet')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('omzet', 'tanggal');

        return [
            'datasets' => [
                [
                    'label' => 'Omzet',
                    'data' => array_values($data->toArray()),
                ],
            ],
            'labels' => array_keys($data->toArray()),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
