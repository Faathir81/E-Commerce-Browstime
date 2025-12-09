<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $sessionCart = session('cart', []); // [product_id => qty]
        $productIds = array_keys($sessionCart);
        $products = $productIds
            ? Produk::whereIn('id', $productIds)->get()
            : collect();

        $cartItems = $products->map(function (Produk $product) use ($sessionCart) {
            $qty = $sessionCart[$product->id] ?? 0;
            return (object) [
                'id'        => $product->id,
                'name'      => $product->nama,
                'price'     => $product->harga,
                'quantity'  => $qty,
                'image_url' => $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/120x120',
            ];
        })->values();

        $subtotal = $cartItems->sum(fn ($item) => $item->price * $item->quantity);
        $deliveryFeeLabel = 'Calculated at checkout';
        $total = $subtotal;

        return view('cart.index', [
            'cartItems'   => $cartItems,
            'subtotal'    => $subtotal,
            'deliveryFeeLabel' => $deliveryFeeLabel,
            'total'       => $total,
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:produks,id'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
        ]);

        $qtyToAdd = max(1, (int) ($validated['quantity'] ?? 1));

        $product = Produk::active()
            ->with(['resep.detail.bahan'])
            ->findOrFail($validated['product_id']);

        if (! $product->hasSufficientStock()) {
            return response()->json([
                'message' => 'Out of stock',
            ], 422);
        }

        $availableUnits = $this->calculateAvailableUnits($product);
        $cart = session()->get('cart', []);
        $currentQty = $cart[$product->id] ?? 0;
        $nextQty = $currentQty + $qtyToAdd;

        if ($availableUnits !== null && $availableUnits >= 0 && $nextQty > $availableUnits) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 422);
        }

        $cart[$product->id] = $nextQty;
        session(['cart' => $cart]);

        $totalQuantity = array_sum($cart);

        return response()->json([
            'totalQuantity' => $totalQuantity,
            'itemQuantity'  => $nextQty,
        ]);
    }

    protected function calculateAvailableUnits(Produk $product): ?int
    {
        $recipe = $product->resep;
        if (! $recipe || $recipe->detail->isEmpty()) {
            return 0;
        }

        $limits = [];
        foreach ($recipe->detail as $detail) {
            $bahan = $detail->bahan;
            $needed = $detail->jumlah ?? 0;
            if (! $bahan || $needed <= 0) {
                return 0;
            }

            $available = floor(($bahan->current_stok ?? 0) / $needed);
            $limits[] = (int) max($available, 0);
        }

        return empty($limits) ? 0 : min($limits);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:increase,decrease'],
        ]);

        $cart = session('cart', []);
        if (! array_key_exists($id, $cart)) {
            return response()->json(['message' => 'Item not in cart'], 404);
        }

        $product = Produk::active()->with(['resep.detail.bahan'])->find($id);
        if (! $product) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            return response()->json(['message' => 'Item unavailable'], 404);
        }

        $availableUnits = $this->calculateAvailableUnits($product);
        $currentQty = $cart[$id];
        $nextQty = $validated['action'] === 'increase' ? $currentQty + 1 : $currentQty - 1;
        if ($availableUnits !== null && $availableUnits >= 0 && $nextQty > $availableUnits) {
            $nextQty = $availableUnits;
        }

        if ($nextQty <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $nextQty;
        }

        session(['cart' => $cart]);

        $totalQuantity = array_sum($cart);
        $subtotal = $this->calculateSubtotal($cart);
        $deliveryFee = 0; // Ongkir dihitung di checkout
        $total = $subtotal;

        return response()->json([
            'itemQuantity'  => $cart[$id] ?? 0,
            'totalQuantity' => $totalQuantity,
            'subtotal'      => $subtotal,
            'deliveryFee'   => $deliveryFee,
            'total'         => $total,
        ]);
    }

    public function remove(int $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        $totalQuantity = array_sum($cart);
        $subtotal = $this->calculateSubtotal($cart);
        $deliveryFee = 0; // Ongkir dihitung di checkout
        $total = $subtotal;

        return response()->json([
            'totalQuantity' => $totalQuantity,
            'subtotal'      => $subtotal,
            'deliveryFee'   => $deliveryFee,
            'total'         => $total,
        ]);
    }

    protected function calculateSubtotal(array $cart): int
    {
        if (empty($cart)) {
            return 0;
        }

        $products = Produk::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $sum = 0;
        foreach ($cart as $productId => $qty) {
            if (isset($products[$productId])) {
                $sum += ((int) $products[$productId]->harga) * ((int) $qty);
            }
        }
        return $sum;
    }
}
