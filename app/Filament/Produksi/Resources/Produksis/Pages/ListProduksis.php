<?php

namespace App\Filament\Produksi\Resources\Produksis\Pages;

use App\Filament\Produksi\Resources\Produksis\ProduksiResource;
use Filament\Resources\Pages\ListRecords;

class ListProduksi extends ListRecords
{
    protected static string $resource = ProduksiResource::class;

    /**
     * Staf produksi tidak boleh create pesanan,
     * jadi kita hilangkan semua header actions.
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
