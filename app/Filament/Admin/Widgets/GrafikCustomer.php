<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pelanggan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class GrafikCustomer extends ChartWidget
{
    protected ?string $heading =  'Total Pelanggan Per Bulan';

    protected function getData(): array
    {
        $endMonth = Carbon::now()->startOfMonth();
        $startMonth = $endMonth->copy()->subMonths(11);

        $raw = Pelanggan::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as jumlah')
            ->whereBetween('created_at', [$startMonth, $endMonth->copy()->endOfMonth()])
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('jumlah', 'ym');

        $labels = [];
        $dataset = [];

        for ($i = 0; $i < 12; $i++) {
            $current = $startMonth->copy()->addMonths($i);
            $key = $current->format('Y-m');
            $labels[] = $current->format('M Y');
            $dataset[] = (int) ($raw[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
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
