<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class SalesToday extends ChartWidget
{
    protected ?string $heading = 'Sales (7 days)';

    protected int | string | array $columnSpan = ['md' => 2, 'xl' => 1];

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $labels = [];
        $data   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            $sum = DB::table('payments')
                ->where('status', 'verified')
                ->whereDate('paid_at', $date->toDateString())
                ->sum('amount');

            $data[] = (float) $sum;
        }

        return [
            'labels'   => $labels,
            'datasets' => [[
                'label' => 'Rp',
                'data'  => $data,
            ]],
        ];
    }
}