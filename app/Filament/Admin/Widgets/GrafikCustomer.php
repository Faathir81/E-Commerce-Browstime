<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pelanggan;
use Filament\Widgets\ChartWidget;

class GrafikCustomer extends ChartWidget
{
    protected ?string $heading =  'Total Pelanggan Per Bulan';

    protected function getData(): array
    {
        $data = Pelanggan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('jumlah', 'bulan');

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => array_values($data->toArray()),
                ],
            ],
            'labels' => array_map(fn ($b) => date('M', mktime(0, 0, 0, $b, 1)), array_keys($data->toArray())),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
