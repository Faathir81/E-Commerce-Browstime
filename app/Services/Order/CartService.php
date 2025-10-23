<?php

namespace App\Services\Order;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

class CartService
{
    protected string $sessionKey = 'cart_items';

    public function all(): Collection
    {
        return collect(Session::get($this->sessionKey, []));
    }

    public function add(Product $product, int $qty): array
    {
        $cart = $this->all();
        $item = $cart->firstWhere('product_id', $product->id);

        $maxQty = $this->getMakeableQty($product->id);
        if ($qty > $maxQty) {
            throw ValidationException::withMessages([
                'qty' => "Only {$maxQty} pcs available.",
            ]);
        }

        if ($item) {
            $newQty = $item['qty'] + $qty;
            if ($newQty > $maxQty) {
                $newQty = $maxQty;
            }
            $cart = $cart->map(fn($i) =>
                $i['product_id'] === $product->id ? array_merge($i, ['qty' => $newQty]) : $i
            );
        } else {
            $cart->push([
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => (int)$product->price,
                'qty'        => $qty,
                'max_qty'    => $maxQty,
            ]);
        }

        Session::put($this->sessionKey, $cart->values()->all());
        return $this->summary();
    }

    public function updateQty(int $productId, int $qty): array
    {
        $cart = $this->all();
        $found = $cart->firstWhere('product_id', $productId);
        if (! $found) {
            throw ValidationException::withMessages(['product' => 'Item not found in cart']);
        }

        $maxQty = $this->getMakeableQty($productId);
        if ($qty > $maxQty) {
            $qty = $maxQty;
        }

        $cart = $cart->map(fn($i) =>
            $i['product_id'] === $productId ? array_merge($i, ['qty' => $qty]) : $i
        );

        Session::put($this->sessionKey, $cart->values()->all());
        return $this->summary();
    }

    public function remove(int $productId): array
    {
        $cart = $this->all()->reject(fn($i) => $i['product_id'] === $productId)->values();
        Session::put($this->sessionKey, $cart->all());
        return $this->summary();
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    public function summary(): array
    {
        $items = $this->all();
        $subtotal = $items->sum(fn($i) => $i['price'] * $i['qty']);
        $totalQty = $items->sum('qty');

        return [
            'items'     => $items->values(),
            'subtotal'  => $subtotal,
            'total_qty' => $totalQty,
        ];
    }

    protected function getMakeableQty(int $productId): int
    {
        $row = \DB::table('v_product_makeable_qty')->where('product_id', $productId)->first();
        return (int) ($row->makeable_qty ?? 0);
    }
}
