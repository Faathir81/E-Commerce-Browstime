<?php

namespace App\Filament\Admin\Resources\AkunBanks\Pages;

use App\Filament\Admin\Resources\AkunBanks\AkunBankResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAkunBanks extends ListRecords
{
    protected static string $resource = AkunBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
