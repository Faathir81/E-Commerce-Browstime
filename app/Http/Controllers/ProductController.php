<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\Catalog\ProductService;
use App\Models\Product;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $query = Product::query()->with(['categories', 'images']);

            // q => search name or slug
            if ($q = $request->query('q')) {
                $query->where(function ($b) use ($q) {
                    $b->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
                });
            }

            // category_id filter (optional strict mode)
            $categoryIdRaw = $request->query('category_id');
            if ($categoryIdRaw !== null && $categoryIdRaw !== '') {
                if (!preg_match('/^\d+$/', (string)$categoryIdRaw)) {
                    if ($request->boolean('strict')) {
                        return response()->json(['message' => 'Invalid category_id'], 400);
                    }
                    // ignore invalid category_id when not strict
                } else {
                    $categoryId = (int) $categoryIdRaw;
                    $query->whereHas('categories', function ($q) use ($categoryId) {
                        $q->where('categories.id', $categoryId);
                    });
                }
            }

            // status filter only if the column exists
            if (Schema::hasColumn('products', 'status') && $request->filled('status')) {
                $query->where('status', $request->query('status'));
            }

            // sorting: whitelist
            $allowedSorts = [
                'name_asc' => ['name', 'asc'],
                'name_desc' => ['name', 'desc'],
                'created_asc' => ['created_at', 'asc'],
                'created_desc' => ['created_at', 'desc'],
            ];
            // add price sorting only if price column exists
            if (Schema::hasColumn('products', 'price')) {
                $allowedSorts['price_asc'] = ['price', 'asc'];
                $allowedSorts['price_desc'] = ['price', 'desc'];
            }

            $sortKey = $request->query('sort', 'created_desc');
            if (!isset($allowedSorts[$sortKey])) {
                // fallback to default
                $sortKey = 'created_desc';
            }
            [$sortColumn, $sortDir] = $allowedSorts[$sortKey];
            $query->orderBy($sortColumn, $sortDir);

            // pagination guards
            $perPage = (int) $request->query('per_page', 10);
            if ($perPage < 1) $perPage = 10;
            if ($perPage > 50) $perPage = 50;

            $page = (int) $request->query('page', 1);
            if ($page < 1) $page = 1;

            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            // shape: add category (first) for convenience, then append makeable_qty in batch
            $collection = $paginator->getCollection()->map(function ($product) {
                if ($product->relationLoaded('categories')) {
                    $product->category = $product->categories->first() ?? null;
                }
                return $product->refresh();
            });

            $collection = $this->productService->appendMakeableQtyToCollection($collection);
            $paginator->setCollection($collection);

            return response()->json([
                'data' => $paginator->getCollection()->values()->all(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        $payload = $request->validated();

        // normalization
        $payload['name'] = trim($payload['name']);
        $slugInput = $payload['slug'] ?? null;
        $payload['slug'] = $slugInput ? Str::slug(trim($slugInput)) : Str::slug($payload['name']);
        if (isset($payload['price'])) {
            $priceStr = str_replace(',', '.', (string) $payload['price']);
            $payload['price'] = is_numeric($priceStr) ? (float) $priceStr : $payload['price'];
        }

        try {
            $created = $this->productService->store($payload);

            $product = null;
            if ($created instanceof Product) {
                $product = $created->load(['categories', 'images']);
            } elseif (is_numeric($created)) {
                $product = Product::with(['categories', 'images'])->find((int)$created);
            } elseif (is_array($created) && isset($created['id'])) {
                $product = Product::with(['categories', 'images'])->find($created['id']);
            } elseif (isset($payload['slug'])) {
                $product = Product::with(['categories', 'images'])->where('slug', $payload['slug'])->first();
            }

            if ($product) {
                // provide single category convenience
                if ($product->relationLoaded('categories')) {
                    $product->category = $product->categories->first() ?? null;
                }
                $product->makeable_qty = $this->productService->getMakeableQty($product->id) ?? 0;
                return response()->json(['data' => $product], 201);
            }

            return response()->json(['data' => $created], 201);
        } catch (QueryException $e) {
            $code = $e->errorInfo[1] ?? $e->getCode();
            if ($code == 1062) {
                return response()->json(['message' => 'Conflict'], 409);
            }
            return response()->json(['message' => 'Could not create product'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function show(int $id)
    {
        $product = Product::with(['categories', 'images'])->find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // provide convenient single category if applicable
        if ($product->relationLoaded('categories')) {
            $product->category = $product->categories->first() ?? null;
        }

        $product->makeable_qty = $this->productService->getMakeableQty($id) ?? 0;

        return response()->json(['data' => $product], 200);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $payload = $request->validated();

        // normalisasi seperti di store
        if (isset($payload['name'])) {
            $payload['name'] = trim($payload['name']);
        }
        if (array_key_exists('slug', $payload)) {
            $slugInput = $payload['slug'] ?? null;
            if ($slugInput) {
                $payload['slug'] = \Illuminate\Support\Str::slug(trim($slugInput));
            } elseif (!empty($payload['name'])) {
                $payload['slug'] = \Illuminate\Support\Str::slug($payload['name']);
            }
        }
        if (isset($payload['price'])) {
            $priceStr = str_replace(',', '.', (string) $payload['price']);
            $payload['price'] = is_numeric($priceStr) ? (float) $priceStr : $payload['price'];
        }

        $product = \App\Models\Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // 1) Lakukan update, tangani error DB spesifik di sini
        try {
            $product = $this->productService->update($id, $payload);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $code = $e->errorInfo[1] ?? $e->getCode();
            if ($code == 1062) {
                return response()->json(['message' => 'Conflict'], 409);
            }
            return response()->json(['message' => 'Could not update product'], 500);
        }

        // 2) Reload product, hitung makeable_qty—kalau gagal, jangan bikin 500
        $product = \App\Models\Product::with(['categories', 'images'])->find($id);
        if ($product && $product->relationLoaded('categories')) {
            $product->category = $product->categories->first() ?? null;
        }

        try {
            $product->makeable_qty = $this->productService->getMakeableQty($id) ?? 0;
        } catch (\Throwable $e) {
            $product->makeable_qty = 0;
        }

        return response()->json([
            'data' => $product,
            'message' => 'Updated successfully',
        ], 200);
    }


    public function destroy(int $id)
    {
        $product = Product::withCount('images')->find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // If product has images, block deletion (consistent choice)
        if (($product->images_count ?? 0) > 0) {
            Log::warning('Product deletion blocked due to existing images', ['product_id' => $id, 'images_count' => $product->images_count]);
            return response()->json([
                'message' => 'Product has images and cannot be deleted.',
                'details' => ['images_count' => $product->images_count],
            ], 409);
        }

        try {
            $this->productService->destroy($id);
            return response()->json(null, 204);
        } catch (\Illuminate\Database\QueryException $e) {
            // Ambil kode error dari beberapa sumber yang mungkin
            $code = $e->errorInfo[1]
                ?? (($e->getPrevious()->errorInfo[1] ?? null) ?? null)
                ?? $e->getCode();

            if ($code == 1451 || $code == 547) {
                \Illuminate\Support\Facades\Log::warning(
                    'Product delete constraint violation',
                    ['product_id' => $id, 'error' => $e->getMessage()]
                );
                return response()->json(['message' => 'Product is referenced by other records and cannot be deleted.'], 409);
            }

            return response()->json(['message' => 'Could not delete product'], 500);
        }
    }
}