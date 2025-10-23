<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function placeOrder(array $buyerData, array $cartItems): Order
    {
        if (empty($cartItems)) {
            throw ValidationException::withMessages(['cart' => 'Cart is empty']);
        }

        return DB::transaction(function () use ($buyerData, $cartItems) {
            $order = Order::create([
                'code'        => $this->generateCode(),
                'buyer_name'  => $buyerData['buyer_name'],
                'buyer_phone' => $buyerData['buyer_phone'],
                'address'     => $buyerData['address'],
                'status'      => 'pending',
                'shipping_fee'=> $buyerData['shipping_fee'] ?? 0,
                'notes'       => $buyerData['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                /** @var Product $product */
                $product = Product::find($item['product_id']);
                if (! $product) continue;

                $makeableQty = $this->getMakeableQty($product->id);
                if ($item['qty'] > $makeableQty) {
                    throw ValidationException::withMessages([
                        'qty' => "{$product->name} limited to {$makeableQty} pcs.",
                    ]);
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'price'      => $product->price,
                    'qty'        => $item['qty'],
                ]);
            }

            return $order;
        });
    }

    public function myOrders(int $userId = null)
    {
        return Order::query()
            ->select(['id','code','buyer_name','status','total','created_at'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function detail(string $code): ?Order
    {
        return Order::query()
            ->where('code', $code)
            ->with(['items.product'])
            ->first();
    }

    protected function generateCode(): string
    {
        return 'ORD-' . strtoupper(Str::random(8));
    }

    protected function getMakeableQty(int $productId): int
    {
        $row = DB::table('v_product_makeable_qty')->where('product_id', $productId)->first();
        return (int)($row->makeable_qty ?? 0);
    }
}
