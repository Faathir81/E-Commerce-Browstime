<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private OrderService $orders) {}

    public function index(): JsonResponse
    {
        $data = $this->orders->myOrders();
        return $this->ok($data);
    }

    public function show(string $code): JsonResponse
    {
        $order = $this->orders->detail($code);
        if (! $order) return $this->notFound();
        return $this->ok($order);
    }

    public function confirmReceived(Order $order): JsonResponse
    {
        $order->update(['status' => 'completed']);
        return $this->ok(['order_code' => $order->code], 'Order marked completed');
    }

    private function ok($data, string $message = 'OK', int $code = 200): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::success($data, $message, $code);
        }
        return response()->json(['message' => $message, 'data' => $data], $code);
    }

    private function notFound(): JsonResponse
    {
        return response()->json(['message' => 'Not Found'], 404);
    }
}
