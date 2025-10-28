<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // biar gak ikut mass-assign ke table products
        unset($data['images'], $data['recipes']);
        return $data;
    }

    protected function afterCreate(): void
    {
        // SIMPAN relasi repeater (images & recipes)
        $this->form->model($this->record)->saveRelationships();

        // fallback cover
        $first = $this->record->images()->first();
        if ($this->record->images()->where('is_cover', 1)->doesntExist() && $first) {
            $first->update(['is_cover' => 1]);
        }
    }
}
