<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    /**
     * Display paginated list of products (public).
     * Supports filter by category, status, and keyword.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories:id,name,slug', 'images:id,product_id,url,sort_order'])
            ->orderBy('created_at', 'desc');

        // Optional filter by category_id (pivot)
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Optional filter by name or keyword
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Optional filter by status (if exists in schema)
        if (Schema::hasColumn('products', 'is_active') && $request->filled('is_active')) {
            $query->where('is_active', (bool) $request->is_active);
        }

        $perPage = min(max((int) $request->get('per_page', 10), 1), 50);
        $paginator = $query->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = DB::transaction(function () use ($request) {
                $product = Product::create($request->validated());

                // Attach categories if provided
                if ($request->filled('categories')) {
                    $product->categories()->sync($request->categories);
                }

                return $product;
            });

            return response()->json([
                'message' => 'Product created successfully.',
                'data' => $product->load(['categories:id,name,slug']),
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error while creating product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a specific product with images & categories.
     */
    public function show(int $id)
    {
        $product = Product::with([
            'categories:id,name,slug',
            'images:id,product_id,url,sort_order',
        ])->find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()->json(['data' => $product]);
    }

    /**
     * Update an existing product.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        try {
            DB::transaction(function () use ($request, $product) {
                $product->update($request->validated());

                if ($request->filled('categories')) {
                    $product->categories()->sync($request->categories);
                }
            });

            return response()->json([
                'message' => 'Product updated successfully.',
                'data' => $product->load(['categories:id,name,slug']),
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error while updating product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product.
     * - 404 if not found
     * - 409 if still has images
     */
    public function destroy(int $id)
    {
        $product = Product::withCount('images')->find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        if ($product->images_count > 0) {
            return response()->json(['message' => 'Cannot delete product with images.'], 409);
        }

        try {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully.'], 204);
        } catch (QueryException $e) {
            // Handle FK constraint conflicts gracefully
            $code = $e->errorInfo[1] ?? null;
            if (in_array($code, [1451, 547])) {
                return response()->json([
                    'message' => 'Cannot delete product, referenced by another record.',
                ], 409);
            }

            return response()->json([
                'message' => 'Database error while deleting product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
