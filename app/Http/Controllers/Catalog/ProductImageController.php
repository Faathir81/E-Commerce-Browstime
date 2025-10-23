<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\ProductImageStoreRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Catalog\ProductImageService;
use Illuminate\Http\JsonResponse;

class ProductImageController extends Controller
{
    public function __construct(private ProductImageService $images) {}

    public function index(int $productId): JsonResponse
    {
        $product = Product::find($productId);
        if (! $product) return $this->notFound();

        $data = $this->images->list($product);
        return $this->ok($data);
    }

    public function store(ProductImageStoreRequest $request, int $productId): JsonResponse
    {
        $product = Product::find($productId);
        if (! $product) return $this->notFound();

        $file = $request->file('image');
        $isCover = (bool)$request->boolean('is_cover');

        $img = $this->images->store($product, $file, $isCover);

        return $this->created([
            'id'       => $img->id,
            'url'      => $this->toPublicUrl($img->path),
            'is_cover' => (bool)$img->is_cover,
        ]);
    }

    public function destroy(ProductImage $image): JsonResponse
    {
        $this->images->destroy($image);
        return $this->ok(null, 'Deleted');
    }

    private function ok($data, string $message = 'OK', int $code = 200): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::success($data, $message, $code);
        }
        return response()->json(['message' => $message, 'data' => $data], $code);
    }

    private function created($data, string $message = 'Created'): JsonResponse
    {
        return $this->ok($data, $message, 201);
    }

    private function notFound(): JsonResponse
    {
        return response()->json(['message' => 'Not Found'], 404);
    }

    private function toPublicUrl(?string $path): ?string
    {
        if (! $path) return null;
        return str_contains($path, 'http')
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    }
}
