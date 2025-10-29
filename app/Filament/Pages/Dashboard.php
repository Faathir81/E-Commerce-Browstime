<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\PendingOrders::class,
            \App\Filament\Widgets\LowStock::class,
            \App\Filament\Widgets\SalesToday::class,
        ];
    }

    public function getColumns(): array|int
    {
        return [
            'sm' => 1,
            'lg' => 2,
            'xl' => 3,
        ];
    }
}
