<?php

namespace App\Services\Catalog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Models\ProductImage;

class ProductImageService
{
    /**
     * List images for a product.
     *
     * @param int $productId
     * @return Collection
     */
    public function listByProduct(int $productId): Collection
    {
        $hasIsCover = Schema::hasColumn('product_images', 'is_cover');

        $select = ['id', 'url'];
        if ($hasIsCover) {
            $select[] = 'is_cover';
        }

        $query = DB::table('product_images')
            ->where('product_id', $productId);

        if ($hasIsCover) {
            $query->orderByDesc('is_cover');
        }

        $rows = $query->orderByDesc('created_at')->get($select);

        return collect($rows)->map(function ($row) use ($hasIsCover) {
            $item = [
                'id' => (int)$row->id,
                'url' => $row->url,
            ];
            if ($hasIsCover) {
                $item['is_cover'] = (bool)($row->is_cover ?? false);
            }
            return $item;
        });
    }

    /**
     * Store an uploaded file for a product.
     *
     * @param int $productId
     * @param UploadedFile $file
     * @param array $options
     * @return array
     */
    public function store(int $productId, UploadedFile $file, array $options = []): array
    {
        $year = date('Y');
        $month = date('m');
        $dir = "products/{$productId}/{$year}/{$month}";

        $ext = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = time() . '_' . Str::uuid() . '.' . $ext;
        $relativePath = "{$dir}/{$filename}";

        // Save to public disk
        $file->storeAs($dir, $filename, 'public');

        $url = Storage::disk('public')->url($relativePath);

        $payload = [
            'product_id' => $productId,
            'url' => $url,
            'sort_order' => $options['sort_order'] ?? 0,
        ];

        $hasIsCover = Schema::hasColumn('product_images', 'is_cover');
        if ($hasIsCover && !empty($options['is_cover'])) {
            $image = null;
            DB::transaction(function () use (&$image, $payload, $productId) {
                DB::table('product_images')->where('product_id', $productId)->update(['is_cover' => 0]);
                $payloadWithCover = $payload + ['is_cover' => 1];
                $image = ProductImage::create($payloadWithCover);
            });
        } else {
            $image = ProductImage::create($payload);
        }

        $out = [
            'id' => $image->id,
            'url' => $image->url,
        ];
        if ($hasIsCover) {
            $out['is_cover'] = (bool)($image->is_cover ?? false);
        }

        return $out;
    }

    /**
     * Set an image as cover for a product (only if is_cover column exists).
     *
     * @param int $productId
     * @param int $imageId
     * @return array
     */
    public function setCover(int $productId, int $imageId): array
    {
        if (! Schema::hasColumn('product_images', 'is_cover')) {
            throw new \RuntimeException('is_cover column not available on product_images.');
        }

        $image = ProductImage::where('id', $imageId)->where('product_id', $productId)->firstOrFail();

        DB::transaction(function () use ($productId, $imageId) {
            DB::table('product_images')->where('product_id', $productId)->update(['is_cover' => 0]);
            DB::table('product_images')->where('id', $imageId)->update(['is_cover' => 1]);
        });

        $image = ProductImage::find($imageId);

        return [
            'id' => $image->id,
            'url' => $image->url,
            'is_cover' => (bool)($image->is_cover ?? false),
        ];
    }

    /**
     * Delete an image belonging to a product.
     *
     * @param int $productId
     * @param int $imageId
     * @return bool
     */
    public function delete(int $productId, int $imageId): bool
    {
        $image = ProductImage::where('id', $imageId)->where('product_id', $productId)->first();
        if (! $image) {
            return false;
        }

        // Try to derive relative path for public disk and delete physical file if present
        $url = $image->url ?? '';
        $path = null;
        if (!empty($url)) {
            $uriPath = parse_url($url, PHP_URL_PATH) ?: '';
            if (str_starts_with($uriPath, '/storage/')) {
                $path = ltrim(substr($uriPath, strlen('/storage/')), '/');
            }
        }

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        return true;
    }
}
