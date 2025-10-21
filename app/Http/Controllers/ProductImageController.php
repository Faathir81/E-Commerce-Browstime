<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * GET /catalog/products/{product}/images
     * List images of a product (public).
     */
    public function index(int $productId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $images = ProductImage::where('product_id', $productId)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($img) {
                return [
                    'id'        => $img->id,
                    'url'       => $img->url, // sudah publik jika accessor di model dibuat
                    'sort_order'=> $img->sort_order,
                ];
            });

        return response()->json(['data' => $images]);
    }

    /**
     * POST /admin/products/{product}/images
     * Upload new image for product.
     */
    public function store(StoreProductImageRequest $request, int $productId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $file = $request->file('image');
        $path = $file->store('products', 'public');

        try {
            $image = ProductImage::create([
                'product_id' => $productId,
                'url'        => $path,
                'sort_order' => $request->input('sort_order', 0),
            ]);

            return response()->json([
                'message' => 'Image uploaded successfully.',
                'data'    => [
                    'id'         => $image->id,
                    'url'        => $image->url, // accessor → URL publik
                    'sort_order' => $image->sort_order,
                ],
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error while saving image.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /admin/products/{product}/images/{image}
     * Delete image from storage & database.
     */
    public function destroy(int $productId, int $imageId)
    {
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $image = ProductImage::where('product_id', $productId)->find($imageId);
        if (! $image) {
            return response()->json(['message' => 'Image not found.'], 404);
        }

        try {
            // Hapus file dari disk jika masih ada
            if (Storage::disk('public')->exists($image->url)) {
                Storage::disk('public')->delete($image->url);
            }

            $image->delete();
            return response()->noContent(); // 204 tanpa body
        } catch (QueryException $e) {
            $code = $e->errorInfo[1] ?? null;
            if (in_array($code, [1451, 547])) {
                return response()->json([
                    'message' => 'Cannot delete image, referenced by another record.',
                ], 409);
            }

            return response()->json([
                'message' => 'Database error while deleting image.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /admin/products/{product}/images/{image}/set-cover
     * Set image as cover (only if column is_cover exists).
     */
    public function setCover(int $productId, int $imageId)
    {
        if (! Schema::hasColumn('product_images', 'is_cover')) {
            return response()->json([
                'message' => 'Feature not supported: `is_cover` column not found.',
            ], 400);
        }

        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $image = ProductImage::where('product_id', $productId)->find($imageId);
        if (! $image) {
            return response()->json(['message' => 'Image not found.'], 404);
        }

        DB::transaction(function () use ($productId, $imageId) {
            ProductImage::where('product_id', $productId)->update(['is_cover' => false]);
            ProductImage::where('id', $imageId)->update(['is_cover' => true]);
        });

        return response()->json(['message' => 'Cover image set successfully.']);
    }
}
