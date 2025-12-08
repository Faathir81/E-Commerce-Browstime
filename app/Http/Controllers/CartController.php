<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
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
}
