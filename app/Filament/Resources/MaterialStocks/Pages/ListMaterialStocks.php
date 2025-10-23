<?php

namespace App\Filament\Resources\MaterialStocks\Pages;

use App\Filament\Resources\MaterialStocks\MaterialStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterialStocks extends ListRecords
{
    protected static string $resource = MaterialStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
