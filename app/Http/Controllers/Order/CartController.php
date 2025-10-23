<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CartAddRequest;
use App\Http\Requests\Order\CartUpdateRequest;
use App\Models\Product;
use App\Services\Order\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): JsonResponse
    {
        return $this->ok($this->cart->summary());
    }

    public function add(CartAddRequest $request): JsonResponse
    {
        $product = Product::findOrFail($request->product_id);
        try {
            $data = $this->cart->add($product, $request->qty);
            return $this->ok($data, 'Added to cart');
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    public function update(CartUpdateRequest $request): JsonResponse
    {
        try {
            $data = $this->cart->updateQty($request->product_id, $request->qty);
            return $this->ok($data, 'Cart updated');
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    public function remove(int $productId): JsonResponse
    {
        $data = $this->cart->remove($productId);
        return $this->ok($data, 'Removed');
    }

    public function clear(): JsonResponse
    {
        $this->cart->clear();
        return $this->ok(null, 'Cart cleared');
    }

    private function ok($data, string $message = 'OK', int $code = 200): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::success($data, $message, $code);
        }
        return response()->json(['message' => $message, 'data' => $data], $code);
    }

    private function error($errors, int $code = 400): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::error($errors, $code);
        }
        return response()->json(['message' => 'Error', 'errors' => $errors], $code);
    }
}
