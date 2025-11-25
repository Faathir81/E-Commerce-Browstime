<?php

namespace App\Filament\Admin\Resources\AkunBanks\Pages;

use App\Filament\Admin\Resources\AkunBanks\AkunBankResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAkunBank extends EditRecord
{
    protected static string $resource = AkunBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
