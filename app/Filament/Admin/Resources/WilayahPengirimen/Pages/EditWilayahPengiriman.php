<?php

namespace App\Filament\Admin\Resources\WilayahPengirimen\Pages;

use App\Filament\Admin\Resources\WilayahPengirimen\WilayahPengirimanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWilayahPengiriman extends EditRecord
{
    protected static string $resource = WilayahPengirimanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
