<?php

namespace App\Services\Catalog;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    public function all(): Collection
    {
        return Category::query()
            ->select(['id', 'name', 'slug'])
            ->orderBy('name')
            ->get();
    }

    public function findBySlugOrId(string|int $key): ?Category
    {
        return is_numeric($key)
            ? Category::find($key)
            : Category::where('slug', $key)->first();
    }
}
