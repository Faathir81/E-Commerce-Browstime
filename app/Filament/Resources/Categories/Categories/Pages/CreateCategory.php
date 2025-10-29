<?php

namespace App\Filament\Resources\Categories\Categories\Pages;

use App\Filament\Resources\Categories\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
