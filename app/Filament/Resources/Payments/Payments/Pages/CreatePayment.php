<?php

namespace App\Filament\Resources\Payments\Payments\Pages;

use App\Filament\Resources\Payments\Payments\PaymentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification; 

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['order_id'] = request()->query('order_id') ?? $data['order_id'] ?? null;

        if ($order = \App\Models\Order::find($data['order_id'])) {
            // SINGLE-PAYMENT GUARD
            if ($order->status === 'paid') {
                Notification::make()
                    ->title('Order ini sudah dibayar.')
                    ->danger()
                    ->send();

                throw new Halt(); // ✅ sekarang ini udah kebaca
            }

            if ($order->payments()->where('status', 'verified')->exists()) {
                Notification::make()
                    ->title('Order ini sudah memiliki pembayaran terverifikasi.')
                    ->danger()
                    ->send();

                throw new Halt();
            }
        }

        // isi default amount jika belum ada
        if (! isset($data['amount']) && $order) {
            $data['amount'] = $order->grand_total;
        }

        return $data;
    }
}