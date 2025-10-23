<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Services\Stock\StockService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReduceStockOnOrderPaid implements ShouldQueue
{
    public function __construct(private StockService $stock) {}

    public function handle(OrderPaid $event): void
    {
        $order = $event->order;

        // Safety: hanya konsumsi sekali
        // (Kalau kamu punya flag/kolom khusus di orders, bisa dicek di sini)
        $this->stock->consumeForOrder($order->id, $order->code);
    }
}
