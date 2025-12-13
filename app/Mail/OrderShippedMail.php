<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Carbon;

class OrderShippedMail extends Mailable
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
            subject: 'Pesanan Anda Sedang Dikirim - ' . $code,
        );
    }

    public function content(): Content
    {
        $etaText = $this->pesanan->eta
            ? Carbon::parse($this->pesanan->eta)->timezone('Asia/Jakarta')->format('d M Y')
            : null;

        $trackingNumber = $this->pesanan->no_resi ?: null;
        $resiText = $trackingNumber ?: 'Nomor resi akan diinformasikan kemudian';

        return new Content(
            view: 'emails.order-shipped',
            with: [
                'customerName' => $this->customerName,
                'orderCode' => $this->pesanan->kode ?? '-',
                'trackingUrl' => $this->trackingUrl,
                'etaText' => $etaText,
                'resiText' => $resiText,
                'trackingNumber' => $trackingNumber,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
