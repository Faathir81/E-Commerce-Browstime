<?php

namespace App\Filament\Admin\Resources\MutasiStoks\Pages;

use App\Filament\Admin\Resources\MutasiStoks\MutasiStokResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMutasiStok extends ViewRecord
{
    protected static string $resource = MutasiStokResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
