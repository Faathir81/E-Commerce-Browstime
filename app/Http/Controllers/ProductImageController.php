<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Services\Catalog\ProductImageService;
use App\Models\Product;

class ProductImageController extends Controller
{
    protected ProductImageService $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->productImageService = $productImageService;
    }

    /**
     * List images for a product.
     *
     * 200 -> data: [ { id, url, is_cover? }, ... ]
     * 404 -> product not found
     */
    public function index(int $productId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $images = $this->productImageService->listByProduct($productId);

        return response()->json(['data' => $images->values()->all()], 200);
    }

    /**
     * Store an uploaded product image.
     *
     * 201 -> created with data
     * 404 -> product not found
     */
    public function store(StoreProductImageRequest $request, int $productId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $file = $request->file('image');

        $options = [];
        if ($request->filled('sort_order')) {
            $options['sort_order'] = (int)$request->input('sort_order');
        }
        if (Schema::hasColumn('product_images', 'is_cover') && $request->filled('is_cover')) {
            $options['is_cover'] = (bool)$request->input('is_cover');
        }

        try {
            $created = $this->productImageService->store($productId, $file, $options);
            return response()->json(['data' => $created], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Could not create image'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * Delete product image.
     *
     * 204 -> deleted
     * 404 -> product or image not found
     */
    public function destroy(int $productId, int $imageId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(null, 404);
        }

        try {
            $deleted = $this->productImageService->delete($productId, $imageId);
            if (! $deleted) {
                return response()->json(null, 404);
            }
            return response()->json(null, 204);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Resource cannot be deleted due to constraint'], 409);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * Set image as cover (optional endpoint).
     *
     * 200 -> data: { id, url, is_cover }
     * 404 -> product or image not found OR feature not available
     */
    public function setCover(int $productId, int $imageId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! Schema::hasColumn('product_images', 'is_cover')) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        try {
            $result = $this->productImageService->setCover($productId, $imageId);
            return response()->json(['data' => $result], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(null, 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}