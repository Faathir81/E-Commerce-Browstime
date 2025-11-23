<?php

namespace App\Filament\Admin\Resources\BahanBakus\Pages;

use App\Filament\Admin\Resources\BahanBakus\BahanBakuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBahanBaku extends EditRecord
{
    protected static string $resource = BahanBakuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
