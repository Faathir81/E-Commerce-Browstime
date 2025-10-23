<?php

namespace App\Services\Stock;

use Illuminate\Support\Facades\DB;

class RecipeService
{
    /**
     * Ambil resep bahan untuk 1 produk.
     * Return: [ [material_id, qty_per_unit], ... ]
     *
     * Asumsi kolom: product_recipes(product_id, material_id, qty)
     * Jika di schema kamu namanya berbeda (mis: qty_per_unit), nanti tinggal disesuaikan.
     */
    public function getRecipe(int $productId): array
    {
        return DB::table('product_recipes')
            ->select(['material_id', 'qty'])
            ->where('product_id', $productId)
            ->get()
            ->map(fn($r) => [
                'material_id'   => (int) $r->material_id,
                'qty_per_unit'  => (float) $r->qty,
            ])
            ->all();
    }

    /**
     * Hitung kebutuhan bahan untuk (product_id, quantity)
     * Output dalam bentuk map [material_id => qty_total]
     */
    public function computeNeedsForItem(int $productId, int $qty): array
    {
        $needs = [];
        foreach ($this->getRecipe($productId) as $row) {
            $needs[$row['material_id']] = ($needs[$row['material_id']] ?? 0) + ($row['qty_per_unit'] * $qty);
        }
        return $needs;
    }

    /**
     * Hitung kebutuhan bahan untuk satu order (berdasar order_items).
     * Return: [material_id => qty_total]
     */
    public function computeNeedsForOrder(int $orderId): array
    {
        $items = DB::table('order_items')
            ->select(['product_id', 'qty'])
            ->where('order_id', $orderId)
            ->get();

        $needs = [];
        foreach ($items as $it) {
            $rowNeeds = $this->computeNeedsForItem((int) $it->product_id, (int) $it->qty);
            foreach ($rowNeeds as $matId => $q) {
                $needs[$matId] = ($needs[$matId] ?? 0) + $q;
            }
        }
        return $needs;
    }
}
