<?php

namespace App\Filament\Resources\MaterialStocks\Pages;

use App\Filament\Resources\MaterialStocks\MaterialStockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaterialStock extends EditRecord
{
    protected static string $resource = MaterialStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
