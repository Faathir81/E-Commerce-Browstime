<?php

namespace App\Services\Catalog;

use App\Models\Category;

class CategoryService
{
    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): ?Category
    {
        $cat = Category::find($id);
        if (! $cat) return null;

        $cat->update($data);
        return $cat;
    }

    /**
     * Hapus kategori hanya jika tidak dipakai produk.
     * - Return false jika tidak ditemukan.
     * - Throw \RuntimeException('in_use') kalau masih direferensikan.
     */
    public function destroy(int $id): bool
    {
        $cat = Category::withCount('products')->find($id);
        if (! $cat) return false;

        if ($cat->products_count > 0) {
            throw new \RuntimeException('in_use');
        }

        $cat->delete();
        return true;
    }
}
