<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\ProductIndexRequest;
use App\Models\Product;
use App\Services\Catalog\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct(private ProductService $products) {}

    public function index(ProductIndexRequest $request): JsonResponse
    {
        $data = $this->products->index($request->validated());
        return $this->ok([
            'items' => $data->items(),
            'meta'  => [
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
                'last_page'    => $data->lastPage(),
            ],
        ]);
    }

    public function show(string $key): JsonResponse
    {
        $product = $this->products->show($key);
        if (! $product) {
            return $this->notFound();
        }
        return $this->ok($product);
    }

    public function store(): JsonResponse
    {
        $data = request()->validate([
            'category_id' => ['nullable','integer','exists:categories,id'],
            'name'        => ['required','string','max:200'],
            'slug'        => ['nullable','string','max:200'],
            'price'       => ['required','integer','min:0'],
            'description' => ['nullable','string'],
            'is_active'   => ['sometimes','boolean'],
        ]);

        try {
            $p = $this->products->store($data);
            return $this->created(['id' => $p->id]);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    public function update(Product $product): JsonResponse
    {
        $data = request()->validate([
            'category_id' => ['nullable','integer','exists:categories,id'],
            'name'        => ['sometimes','string','max:200'],
            'slug'        => ['sometimes','string','max:200'],
            'price'       => ['sometimes','integer','min:0'],
            'description' => ['nullable','string'],
            'is_active'   => ['sometimes','boolean'],
        ]);

        try {
            $p = $this->products->update($product, $data);
            return $this->ok(['id' => $p->id], 'Updated');
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->products->delete($product);
            return $this->ok(null, 'Deleted');
        } catch (ValidationException $e) {
            // 409 jika masih ada images
            return $this->error($e->errors(), 409);
        }
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

    private function error($errors, int $code = 400): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::error($errors, $code);
        }
        return response()->json(['message' => 'Error', 'errors' => $errors], $code);
    }

    private function notFound(): JsonResponse
    {
        return $this->error(['message' => 'Not Found'], 404);
    }
}
