<?php

namespace App\Services\Catalog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB as DBFacade;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;

class ProductService
{
    /**
     * Get makeable quantity for a single product from the view.
     *
     * @param int $productId
     * @return int|null
     */
    public function getMakeableQty(int $productId): ?int
    {
        $qty = DBFacade::table('v_product_makeable_qty')
            ->where('product_id', $productId)
            ->value('makeable_qty');

        if ($qty === null) {
            return null;
        }

        return is_numeric($qty) ? (int) $qty : null;
    }

    /**
     * Map product ids to makeable_qty using a single query.
     * Missing ids are defaulted to 0.
     *
     * @param int[] $productIds
     * @return array [product_id => makeable_qty]
     */
    public function mapMakeableForProductIds(array $productIds): array
    {
        $productIds = array_values(array_unique(array_filter($productIds, function ($v) {
            return $v !== null && $v !== '';
        })));

        if (empty($productIds)) {
            return [];
        }

        $rows = DBFacade::table('v_product_makeable_qty')
            ->whereIn('product_id', $productIds)
            ->get(['product_id', 'makeable_qty']);

        $map = [];
        foreach ($productIds as $id) {
            $map[(int) $id] = 0;
        }

        foreach ($rows as $row) {
            $map[(int) $row->product_id] = $row->makeable_qty === null ? 0 : (int) $row->makeable_qty;
        }

        return $map;
    }

    /**
     * Append makeable_qty to a collection of products (array or model).
     * Uses a single query (no N+1).
     *
     * @param Collection $products
     * @param string $key
     * @return Collection
     */
    public function appendMakeableQtyToCollection(Collection $products, string $key = 'makeable_qty'): Collection
    {
        $ids = $products->pluck('id')->filter()->unique()->values()->all();

        if (empty($ids)) {
            return $products->map(function ($item) use ($key) {
                if (is_array($item)) {
                    $item[$key] = 0;
                } elseif (is_object($item)) {
                    $item->{$key} = 0;
                }
                return $item;
            });
        }

        $map = $this->mapMakeableForProductIds($ids);

        return $products->map(function ($item) use ($map, $key) {
            $id = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);
            $value = 0;
            if ($id !== null && isset($map[(int) $id])) {
                $value = (int) $map[(int) $id];
            }

            if (is_array($item)) {
                $item[$key] = $value;
            } elseif (is_object($item)) {
                $item->{$key} = $value;
            }

            return $item;
        });
    }

    // CRUD minimal implementations (no heavy business logic)

    /**
     * TODO: refine return type
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return DB::transaction(function () use ($data) {
            $categoryId = $data['category_id'] ?? null;
            $payload = $data;
            unset($payload['category_id']);

            $product = Product::create($payload);

            if ($categoryId !== null && method_exists($product, 'categories')) {
                $product->categories()->sync([(int)$categoryId]);
            }

            return $product->refresh();
        });
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): \App\Models\Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = Product::findOrFail($id);

            $categoryId = $data['category_id'] ?? null;
            $payload = $data;
            unset($payload['category_id']);

            $product->update($payload);

            if ($categoryId !== null && method_exists($product, 'categories')) {
                $product->categories()->sync([(int)$categoryId]);
            }

            return $product->refresh();
        });
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $product = Product::find($id);
        if (! $product) {
            return false;
        }

        return DB::transaction(function () use ($product) {
            if (method_exists($product, 'categories')) {
                $product->categories()->detach();
            }
            $product->delete();
            return true;
        });
    }

    /**
     * Alias untuk konsistensi controller yang memakai "delete".
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->destroy($id);
    }
}