<?php

namespace App\Services\Catalog;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    /**
     * Simpan gambar (upload ke storage + insert DB).
     * Jika insert DB gagal, file dihapus agar tidak orphan.
     */
    public function store(int $productId, UploadedFile $file, int $sortOrder = 0): ProductImage
    {
        // 1) Simpan file ke disk 'public', path relatif (products/xxx.ext)
        $path = $file->store('products', 'public');

        try {
            // 2) Insert DB dalam transaksi
            return DB::transaction(function () use ($productId, $path, $sortOrder) {
                return ProductImage::create([
                    'product_id' => $productId,
                    'url'        => $path,      // simpan PATH, accessor akan jadikan URL publik
                    'sort_order' => $sortOrder,
                ]);
            });
        } catch (\Throwable $e) {
            // 3) Rollback file bila DB gagal
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    /**
     * Hapus gambar (file + record).
     * Return false jika image tidak ditemukan.
     */
    public function destroy(int $productId, int $imageId): bool
    {
        $img = ProductImage::where('product_id', $productId)->find($imageId);
        if (! $img) return false;

        if ($img->getRawOriginal('url') && Storage::disk('public')->exists($img->getRawOriginal('url'))) {
            Storage::disk('public')->delete($img->getRawOriginal('url'));
        }

        $img->delete();
        return true;
    }
}