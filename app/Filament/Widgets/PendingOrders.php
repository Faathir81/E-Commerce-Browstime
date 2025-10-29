<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class PendingOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $count = DB::table('orders')
            ->whereIn('status', ['pending', 'awaiting_payment'])
            ->count();

        return [
            Stat::make('Pending Orders', (string) $count)
                ->description('pending + awaiting_payment'),
        ];
    }
}
