<?php

namespace App\Observers;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageObserver
{
    /**
     * Hapus file di storage saat image dihapus.
     */
    public function deleted(ProductImage $image): void
    {
        $path = $image->path ?? $image->file ?? null;
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Bisa juga tambahkan logic: saat ganti cover → nonaktifkan cover lain.
     * Tapi untuk sekarang cukup handle delete saja.
     */
}
