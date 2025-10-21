<?php

namespace App\Services\Catalog;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Create product + (opsional) sync categories.
     * @param array $data => field product + ['categories' => [id,...]] (opsional)
     */
    public function store(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $categories = $data['categories'] ?? null;
            unset($data['categories']);

            /** @var Product $product */
            $product = Product::create($data);

            if ($categories) {
                $product->categories()->sync($categories);
            }

            return $product->load(['categories:id,name,slug']);
        });
    }

    /**
     * Update product + (opsional) sync categories.
     * Return null jika product tidak ditemukan.
     */
    public function update(int $id, array $data): ?Product
    {
        return DB::transaction(function () use ($id, $data) {
            /** @var Product|null $product */
            $product = Product::find($id);
            if (! $product) {
                return null;
            }

            $categories = $data['categories'] ?? null;
            unset($data['categories']);

            $product->update($data);

            if ($categories !== null) {
                $product->categories()->sync($categories);
            }

            return $product->load(['categories:id,name,slug']);
        });
    }

    /**
     * Hapus product.
     * - Return false jika tidak ditemukan.
     * - Throw \RuntimeException('has_images') jika masih punya images.
     */
    public function destroy(int $id): bool
    {
        /** @var Product|null $product */
        $product = Product::withCount('images')->find($id);
        if (! $product) {
            return false;
        }

        if ($product->images_count > 0) {
            throw new \RuntimeException('has_images');
        }

        $product->delete();
        return true;
    }
}
