<?php

namespace App\Mail;

use App\Models\Pesanan;
use App\Support\StatusStyle;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderCreatedMail extends Mailable
{
    public function __construct(
        public Pesanan $pesanan,
        public string $customerName,
        public string $trackingUrl
    ) {
    }

    public function envelope(): Envelope
    {
        $code = $this->pesanan->kode ?? 'Pesanan';

        return new Envelope(
            subject: 'Order Confirmation - ' . $code,
        );
    }

    public function content(): Content
    {
        $statusLabel = StatusStyle::pesanan($this->pesanan->status)['label'] ?? ucfirst($this->pesanan->status ?? '');
        $totalDisplay = number_format((float) ($this->pesanan->total ?? 0), 0, ',', '.');

        return new Content(
            view: 'emails.order-created',
            with: [
                'customerName' => $this->customerName,
                'orderCode' => $this->pesanan->kode ?? '-',
                'statusLabel' => $statusLabel,
                'totalDisplay' => $totalDisplay,
                'trackingUrl' => $this->trackingUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
