<?php

namespace App\Services\Catalog;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductImageService
{
    public function list(Product $product)
    {
        return $product->images()
            ->select(['id', 'product_id', 'path', 'is_cover'])
            ->orderByDesc('is_cover')
            ->orderBy('id')
            ->get()
            ->map(fn(ProductImage $img) => [
                'id'       => $img->id,
                'url'      => $this->toPublicUrl($img->path),
                'is_cover' => (bool)$img->is_cover,
            ]);
    }

    public function store(Product $product, UploadedFile $file, bool $isCover = false): ProductImage
    {
        $path = $file->store('products', 'public');

        if ($isCover) {
            $product->images()->update(['is_cover' => false]);
        }

        return $product->images()->create([
            'path'      => $path,
            'is_cover'  => $isCover,
        ]);
    }

    public function destroy(ProductImage $image): void
    {
        $path = $image->path;
        $image->delete();

        // Hapus file fisik (observer juga boleh, tapi aman di sini)
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function toPublicUrl(?string $path): ?string
    {
        if (! $path) return null;
        return str_contains($path, 'http')
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    }
}
