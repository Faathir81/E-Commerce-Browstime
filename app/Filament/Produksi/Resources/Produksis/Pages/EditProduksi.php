<?php

namespace App\Filament\Produksi\Resources\Produksis\Pages;

use App\Filament\Produksi\Resources\Produksis\ProduksiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProduksi extends EditRecord
{
    protected static string $resource = ProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
