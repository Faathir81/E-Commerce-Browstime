<?php

namespace App\Filament\Admin\Resources\ResepBOMS\Pages;

use App\Filament\Admin\Resources\ResepBOMS\ResepBOMResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditResepBOM extends EditRecord
{
    protected static string $resource = ResepBOMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
