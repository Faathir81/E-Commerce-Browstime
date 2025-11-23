<?php

namespace App\Filament\Admin\Resources\BahanBakus\Pages;

use App\Filament\Admin\Resources\BahanBakus\BahanBakuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBahanBakus extends ListRecords
{
    protected static string $resource = BahanBakuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
