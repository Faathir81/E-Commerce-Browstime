<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen\Pages;

use App\Filament\Admin\Resources\WilayahPengirimen\WilayahPengirimanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWilayahPengirimen extends ListRecords
{
    protected static string $resource = WilayahPengirimanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
