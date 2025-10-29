<?php

namespace App\Filament\Resources\Categories\Categories\Pages;

use App\Filament\Resources\Categories\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
