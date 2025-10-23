<?php

namespace App\Services\Stock;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function __construct(private RecipeService $recipes) {}

    /**
     * Ambil stok current dari view v_material_stock_current.
     * Return: map [material_id => qty_current]
     *
     * Asumsi view punya kolom: material_id, qty_current (atau total_qty).
     * Kalau nama kolom berbeda, tinggal disesuaikan 1 baris map-nya.
     */
    public function getCurrentStockMap(): array
    {
        $rows = DB::table('v_material_stock_current')->get();

        // Coba deteksi nama kolom qty di view
        $map = [];
        foreach ($rows as $r) {
            $qty = null;
            if (property_exists($r, 'qty_current')) {
                $qty = $r->qty_current;
            } elseif (property_exists($r, 'total_qty')) {
                $qty = $r->total_qty;
            } elseif (property_exists($r, 'qty')) {
                $qty = $r->qty;
            } else {
                // fallback, anggap kolom kedua adalah qty
                $arr = (array) $r;
                $vals = array_values($arr);
                $qty = $vals[1] ?? 0;
            }
            $map[(int) $r->material_id] = (float) $qty;
        }
        return $map;
    }

    /**
     * Validasi apakah stok cukup untuk memenuhi kebutuhan order.
     * Throw 422 jika tidak cukup.
     */
    public function assertSufficientForOrder(int $orderId): void
    {
        $needs = $this->recipes->computeNeedsForOrder($orderId);
        if (empty($needs)) return;

        $stock = $this->getCurrentStockMap();

        $insufficient = [];
        foreach ($needs as $matId => $needQty) {
            $curr = $stock[$matId] ?? 0;
            if ($curr + 1e-9 < $needQty) {
                $insufficient[] = [
                    'material_id' => $matId,
                    'needed'      => $needQty,
                    'available'   => $curr,
                ];
            }
        }

        if (! empty($insufficient)) {
            throw ValidationException::withMessages([
                'stock' => 'Insufficient materials for this order.',
                'detail' => $insufficient,
            ]);
        }
    }

    /**
     * Konsumsi stok bahan untuk sebuah order (ketika order sudah "paid").
     *
     * Asumsi tabel material_stocks mendukung pencatatan penyesuaian:
     * kolom umum yang dipakai: material_id, qty (boleh negatif), type, note, order_id, created_at
     * Jika skema kamu menggunakan kolom berbeda (misal qty_out), tinggal sesuaikan 1 bagian insert.
     */
    public function consumeForOrder(int $orderId, string $orderCode): void
    {
        $needs = $this->recipes->computeNeedsForOrder($orderId);
        if (empty($needs)) return;

        // Pastikan cukup sebelum konsumsi
        $this->assertSufficientForOrder($orderId);

        $now = now();

        DB::transaction(function () use ($orderId, $orderCode, $needs, $now) {
            $rows = [];
            foreach ($needs as $matId => $needQty) {
                $rows[] = [
                    'material_id' => $matId,
                    // catat sebagai negatif (konsumsi)
                    'qty'         => -1 * $needQty,
                    // kolom opsional — aman jika tidak ada (DB akan abaikan kalau tidak ada kolom tsb)
                    'type'        => 'out',
                    'note'        => "consume by order {$orderCode}",
                    'order_id'    => $orderId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            // insert menggunakan query builder agar fleksibel dengan kolom opsional
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('material_stocks')->insert($chunk);
            }
        });
    }
}
