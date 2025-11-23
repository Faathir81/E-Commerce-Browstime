<?php

namespace App\Filament\Admin\Resources\ResepBOMS\Pages;

use App\Filament\Admin\Resources\ResepBOMS\ResepBOMResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResepBOMS extends ListRecords
{
    protected static string $resource = ResepBOMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
