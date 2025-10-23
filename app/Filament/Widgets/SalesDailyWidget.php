<?php

namespace App\Filament\Widgets;

use App\Services\Report\ReportService;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class SalesDailyWidget extends ChartWidget
{
    // harus PUBLIC agar kompatibel dengan parent
    public function getHeading(): string|Htmlable|null
    {
        return 'Sales (Last 7 Days)';
    }

    protected function getData(): array
    {
        /** @var ReportService $report */
        $report = app(ReportService::class);

        $to   = now()->toDateString();
        $from = now()->subDays(6)->toDateString();

        $rows = $report->salesDaily($from, $to);
        if (! is_array($rows)) {
            $rows = $rows?->toArray() ?? [];
        }

        $labels  = array_map(fn ($r) => substr((string) $r['date'], 5), $rows); // "MM-DD"
        $orders  = array_map(fn ($r) => (int) ($r['orders'] ?? 0), $rows);
        $revenue = array_map(fn ($r) => (float) ($r['revenue'] ?? 0), $rows);

        return [
            'datasets' => [
                ['label' => 'Orders',  'data' => $orders],
                ['label' => 'Revenue', 'data' => $revenue],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
