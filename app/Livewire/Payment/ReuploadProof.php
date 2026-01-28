<?php

namespace App\Livewire\Payment;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ReuploadProof extends Component
{
    use WithFileUploads;

    public string $orderCode;
    public ?string $proofUrl;
    public bool $canReupload = false;
    public $payment_proof;

    public function mount(string $orderCode, ?string $proofUrl = null, bool $canReupload = false): void
    {
        $this->orderCode = $orderCode;
        $this->proofUrl = $proofUrl;
        $this->canReupload = $canReupload;
    }

    public function submit(): void
    {
        if (! $this->canReupload) {
            return;
        }

        $this->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $pesanan = Pesanan::where('kode', $this->orderCode)->first();
        $pembayaran = $pesanan?->pembayaran;

        if (! $pembayaran || ($pembayaran->metode ?? null) === 'midtrans') {
            return;
        }

        $oldPath = $pembayaran->bukti_bayar;
        $path = $this->payment_proof->store('payment_proofs', 'public');

        $pembayaran->update([
            'bukti_bayar' => $path,
            'status' => 'menunggu_verifikasi',
        ]);

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $this->proofUrl = Storage::disk('public')->url($path);
        $this->payment_proof = null;

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Bukti pembayaran berhasil diunggah ulang.',
        ]);
    }

    public function getIsTempImageProperty(): bool
    {
        return $this->payment_proof instanceof TemporaryUploadedFile
            && str_starts_with($this->payment_proof->getMimeType(), 'image/');
    }

    public function render()
    {
        return view('livewire.payment.reupload-proof');
    }
}
