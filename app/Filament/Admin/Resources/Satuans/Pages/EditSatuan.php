<?php

namespace App\Filament\Admin\Resources\Satuans\Pages;

use App\Filament\Admin\Resources\Satuans\SatuanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSatuan extends EditRecord
{
    protected static string $resource = SatuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
