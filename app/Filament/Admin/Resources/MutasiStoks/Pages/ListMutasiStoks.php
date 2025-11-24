<?php

namespace App\Filament\Admin\Resources\MutasiStoks\Pages;

use App\Filament\Admin\Resources\MutasiStoks\MutasiStokResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMutasiStoks extends ListRecords
{
    protected static string $resource = MutasiStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
