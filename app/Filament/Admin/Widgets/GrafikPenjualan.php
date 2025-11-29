<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GrafikPenjualan extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan (7 Hari Terakhir)';
    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $data = Pesanan::query()
            ->leftJoin('pembayarans as pay', function ($join) {
                $join->on('pay.pesanan_id', '=', 'pesanans.id')
                    ->where('pay.status', 'valid');
            })
            ->whereIn('pesanans.status', ['paid', 'dikirim'])
            ->whereDate(DB::raw('COALESCE(pay.created_at, pesanans.updated_at)'), '>=', now()->subDays(6))
            ->selectRaw('DATE(COALESCE(pay.created_at, pesanans.updated_at)) as tanggal, SUM(pesanans.total) as omzet')
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
