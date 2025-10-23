<?php

namespace App\Services\Catalog;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductService
{
    /**
     * List products with optional filters + makeable_qty from DB view.
     */
    public function index(array $filters): LengthAwarePaginator
    {
        $q = $filters['q'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $perPage = (int)($filters['per_page'] ?? 12);
        $sort = $filters['sort'] ?? 'newest'; // newest|price_asc|price_desc|name

        $base = Product::query()
            ->select([
                'products.id',
                'products.name',
                'products.slug',
                'products.price',
                DB::raw('COALESCE(vpmq.makeable_qty, 0) as makeable_qty'),
            ])
            ->leftJoin('v_product_makeable_qty as vpmq', 'vpmq.product_id', '=', 'products.id')
            ->with(['images' => function ($q) {
                $q->select('id', 'product_id', 'path', 'is_cover');
            }])
            ->where('products.is_active', true);

        if ($q) {
            $base->where(function (Builder $qb) use ($q) {
                $qb->where('products.name', 'like', "%{$q}%")
                   ->orWhere('products.slug', 'like', "%{$q}%");
            });
        }

        if ($categoryId) {
            $base->where('products.category_id', $categoryId);
        }

        $base->when($sort === 'newest', fn($qb) => $qb->orderByDesc('products.created_at'))
             ->when($sort === 'price_asc', fn($qb) => $qb->orderBy('products.price'))
             ->when($sort === 'price_desc', fn($qb) => $qb->orderByDesc('products.price'))
             ->when($sort === 'name', fn($qb) => $qb->orderBy('products.name'));

        return $base->paginate($perPage)->withQueryString();
    }

    /**
     * Show single product (by slug or id) with images + makeable_qty.
     */
    public function show(string|int $key): ?array
    {
        /** @var Product|null $product */
        $product = is_numeric($key)
            ? Product::query()->where('id', $key)->first()
            : Product::query()->where('slug', $key)->first();

        if (! $product) {
            return null;
        }

        $row = DB::table('v_product_makeable_qty')->where('product_id', $product->id)->first();
        $makeable = $row?->makeable_qty ?? 0;

        $images = $product->images()
            ->select(['id', 'product_id', 'path', 'is_cover'])
            ->orderByDesc('is_cover')
            ->orderBy('id')
            ->get()
            ->map(fn(ProductImage $img) => [
                'id'       => $img->id,
                'url'      => $this->toPublicUrl($img->path),
                'is_cover' => (bool)$img->is_cover,
            ]);

        return [
            'id'            => $product->id,
            'name'          => $product->name,
            'slug'          => $product->slug,
            'price'         => (int)$product->price,
            'description'   => $product->description,
            'makeable_qty'  => (int)$makeable,
            'images'        => $images,
        ];
    }

    /**
     * Create product (basic). Slug auto from name if empty.
     * NOTE: Admin CRUD utama via Filament; endpoint ini dipakai kalau butuh API.
     */
    public function store(array $data): Product
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $exists = Product::where('slug', $data['slug'])->exists();
        if ($exists) {
            throw ValidationException::withMessages(['slug' => 'Slug already exists.']);
        }

        /** @var Product $p */
        $p = Product::create([
            'category_id' => $data['category_id'] ?? null,
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'price'       => $data['price'],
            'description' => $data['description'] ?? null,
            'is_active'   => $data['is_active'] ?? true,
        ]);

        return $p;
    }

    public function update(Product $product, array $data): Product
    {
        if (isset($data['slug']) && $data['slug'] !== $product->slug) {
            $exists = Product::where('slug', $data['slug'])->where('id', '<>', $product->id)->exists();
            if ($exists) {
                throw ValidationException::withMessages(['slug' => 'Slug already exists.']);
            }
        }

        $product->fill([
            'category_id' => $data['category_id'] ?? $product->category_id,
            'name'        => $data['name'] ?? $product->name,
            'slug'        => $data['slug'] ?? $product->slug,
            'price'       => $data['price'] ?? $product->price,
            'description' => $data['description'] ?? $product->description,
            'is_active'   => $data['is_active'] ?? $product->is_active,
        ])->save();

        return $product;
    }

    /**
     * Prevent delete when images still exist (409).
     */
    public function delete(Product $product): void
    {
        $hasImages = $product->images()->exists();
        if ($hasImages) {
            throw ValidationException::withMessages([
                'product' => 'Cannot delete product with existing images.',
            ]);
        }

        $product->delete();
    }

    private function toPublicUrl(?string $path): ?string
    {
        if (! $path) return null;
        return str_contains($path, 'http')
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    }
}
