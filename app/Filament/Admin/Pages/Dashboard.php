<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\GrafikPenjualan;
use App\Filament\Admin\Widgets\PesananTerbaruTable;
use App\Filament\Admin\Widgets\RingkasanStatistik;
use App\Filament\Admin\Widgets\GrafikCustomer;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            FilamentInfoWidget::class,
            RingkasanStatistik::class,
            GrafikPenjualan::class,
            GrafikCustomer::class,
            PesananTerbaruTable::class,
        ];
    }

    public function getColumns(): array
    {
        return [
            'md' => 1,
            'xl' => 2,
        ];
    }
}
