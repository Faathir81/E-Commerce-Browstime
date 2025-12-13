<?php

namespace App\Observers;

use App\Models\Pesanan;
use App\Support\OrderNotifier;

class PesananObserver
{
    public function updated(Pesanan $pesanan): void
    {
        if (
            $pesanan->wasChanged('status') &&
            $pesanan->status === Pesanan::STATUS_DIKIRIM
        ) {
            OrderNotifier::sendOrderShipped($pesanan);
        }
    }
}
