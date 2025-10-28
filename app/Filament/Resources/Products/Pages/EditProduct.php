<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['images'], $data['recipes']);
        return $data;
    }

    protected function afterSave(): void
    {
        // SIMPAN relasi repeater (images & recipes)
        $this->form->model($this->record)->saveRelationships();

        // jaga tepat satu cover
        $cover = $this->record->images()->where('is_cover', 1)->first();
        if (! $cover) {
            $first = $this->record->images()->first();
            if ($first) $first->update(['is_cover' => 1]);
        } else {
            $this->record->images()->whereKeyNot($cover->id)->where('is_cover', 1)->update(['is_cover' => 0]);
        }
    }
}
