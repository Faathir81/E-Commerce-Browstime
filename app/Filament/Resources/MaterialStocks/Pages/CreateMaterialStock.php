<?php

namespace App\Filament\Resources\MaterialStocks\Pages;

use App\Filament\Resources\MaterialStocks\MaterialStockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterialStock extends CreateRecord
{
    protected static string $resource = MaterialStockResource::class;
}
