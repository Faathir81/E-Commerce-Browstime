<?php

namespace App\Filament\Admin\Resources\Satuans\Pages;

use App\Filament\Admin\Resources\Satuans\SatuanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSatuans extends ListRecords
{
    protected static string $resource = SatuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
