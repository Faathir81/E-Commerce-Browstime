<?php

namespace App\Services\Report;

use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class ReportService
{
    /**
     * Rekap penjualan harian dalam rentang tanggal (inclusive).
     * Menghitung dari orders + order_items agar tidak bergantung kolom 'total' di table orders.
     *
     * @param string $dateFrom format 'Y-m-d'
     * @param string $dateTo   format 'Y-m-d'
     * @return array [ ['date' => '2025-10-01', 'orders' => 12, 'revenue' => 3500000], ... ]
     */
    public function salesDaily(string $dateFrom, string $dateTo): array
    {
        // Ambil agregat dari order_items yang status-nya paid/shipped/completed
        $rows = DB::table('orders as o')
            ->join('order_items as oi', 'oi.order_id', '=', 'o.id')
            ->selectRaw("DATE(o.created_at) as d, COUNT(DISTINCT o.id) as orders_count, SUM(oi.price_frozen * oi.qty) + COALESCE(SUM(o.shipping_cost), 0) as revenue")
            ->whereIn('o.status', ['paid', 'shipped', 'completed'])
            ->whereDate('o.created_at', '>=', $dateFrom)
            ->whereDate('o.created_at', '<=', $dateTo)
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        // Normalisasi agar hari tanpa transaksi tetap muncul (nilai 0)
        $map = [];
        foreach ($rows as $r) {
            $map[$r->d] = [
                'date'    => $r->d,
                'orders'  => (int) $r->orders_count,
                'revenue' => (int) $r->revenue,
            ];
        }

        $period = CarbonPeriod::create($dateFrom, $dateTo);
        $result = [];
        foreach ($period as $day) {
            $key = $day->format('Y-m-d');
            $result[] = $map[$key] ?? [
                'date'    => $key,
                'orders'  => 0,
                'revenue' => 0,
            ];
        }
        return $result;
    }

    /**
     * Snapshot stok bahan baku saat ini dari view v_material_stock_current.
     * @return array [ ['material_id'=>1,'qty'=>10.5], ... ]
     */
    public function stockCurrent(): array
    {
        $rows = DB::table('v_material_stock_current')->get();

        $out = [];
        foreach ($rows as $r) {
            // Deteksi nama kolom qty di view (qty_current|total_qty|qty)
            $qty = null;
            if (property_exists($r, 'qty_current')) {
                $qty = $r->qty_current;
            } elseif (property_exists($r, 'total_qty')) {
                $qty = $r->total_qty;
            } elseif (property_exists($r, 'qty')) {
                $qty = $r->qty;
            } else {
                $arr = (array) $r;
                $vals = array_values($arr);
                $qty = $vals[1] ?? 0;
            }

            $out[] = [
                'material_id' => (int) $r->material_id,
                'qty'         => (float) $qty,
            ];
        }
        return $out;
    }

    /**
     * Ambil 5 bahan baku dengan stok terendah (untuk widget).
     * Jika view menyediakan nama bahan, bisa join ke materials.
     */
    public function lowestStock(int $limit = 5): array
    {
        $rows = DB::table('v_material_stock_current as v')
            ->join('materials as m', 'm.id', '=', 'v.material_id')
            ->select(['v.material_id', 'm.name', DB::raw('COALESCE(v.qty_current, 0) as qty')])
            ->orderBy('qty')
            ->limit($limit)
            ->get();

        return $rows->map(fn($r) => [
            'material_id' => (int) $r->material_id,
            'name'        => (string) $r->name,
            'qty'         => (float) $r->qty,
        ])->all();
    }

    /**
     * Ringkasan cepat untuk dashboard: today/week/month.
     */
    public function summaryForDashboard(): array
    {
        $today   = now()->toDateString();
        $weekAgo = now()->copy()->subDays(6)->toDateString();
        $monthAgo= now()->copy()->subDays(29)->toDateString();

        $todayAgg = $this->aggregateRange($today, $today);
        $weekAgg  = $this->aggregateRange($weekAgo, $today);
        $monthAgg = $this->aggregateRange($monthAgo, $today);

        return [
            'today'  => $todayAgg,
            'week'   => $weekAgg,
            'month'  => $monthAgg,
        ];
    }

    protected function aggregateRange(string $from, string $to): array
    {
        $row = DB::table('orders as o')
            ->join('order_items as oi', 'oi.order_id', '=', 'o.id')
            ->selectRaw("COUNT(DISTINCT o.id) as orders_count, SUM(oi.price_frozen * oi.qty) + COALESCE(SUM(o.shipping_cost), 0) as revenue")
            ->whereIn('o.status', ['paid', 'shipped', 'completed'])
            ->whereDate('o.created_at', '>=', $from)
            ->whereDate('o.created_at', '<=', $to)
            ->first();

        return [
            'orders'  => (int) ($row->orders_count ?? 0),
            'revenue' => (int) ($row->revenue ?? 0),
            'from'    => $from,
            'to'      => $to,
        ];
    }
}
