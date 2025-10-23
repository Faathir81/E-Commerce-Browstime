<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Services\Order\CartService;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private OrderService $orders
    ) {}

    public function store(CheckoutRequest $request): JsonResponse
    {
        $cart = $this->cart->all();
        try {
            $order = $this->orders->placeOrder($request->validated(), $cart->toArray());
            $this->cart->clear();

            return $this->ok([
                'order_code' => $order->code,
                'message'    => 'Order created. Please proceed to payment.',
            ], 'Order created', 201);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
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
