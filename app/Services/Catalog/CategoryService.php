<?php

namespace App\Services\Catalog;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryService
{
    /**
     * List categories.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return Category::all();
    }

    /**
     * Create category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            return Category::create($data);
        });
    }

    /**
     * Update category.
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function update(int $id, array $data): Category
    {
        return DB::transaction(function () use ($id, $data) {
            $category = Category::findOrFail($id);
            $category->update($data);
            return $category->refresh();
        });
    }

    /**
     * Delete category.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $category = Category::find($id);
        if (! $category) {
            return false;
        }

        return DB::transaction(function () use ($category) {
            $category->delete();
            return true;
        });
    }
}