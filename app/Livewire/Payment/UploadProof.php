<?php

namespace App\Livewire\Payment;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class UploadProof extends Component
{
    use WithFileUploads;

    public string $orderCode;
    public ?Pesanan $pesanan = null;
    public ?Pembayaran $pembayaran = null;
    public $payment_proof;

    public function mount(string $order)
    {
        $this->orderCode = $order;
        $this->pesanan = Pesanan::where('kode', $order)->firstOrFail();
        $this->pembayaran = $this->pesanan->pembayaran;

        if (! $this->pembayaran) {
            abort(404, 'Pembayaran tidak ditemukan.');
        }

        if (Auth::check() && $this->pesanan->user_id && $this->pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($this->pembayaran->metode === 'midtrans') {
            return redirect()->route('order.success', ['kode' => $order]);
        }
    }

    public function submit(): \Illuminate\Http\RedirectResponse
    {
        $this->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $pembayaran = $this->pembayaran;

        if (! $pembayaran || $pembayaran->metode === 'midtrans') {
            return redirect()->route('order.success', ['kode' => $this->orderCode]);
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

        session()->flash('status', 'Bukti pembayaran berhasil diunggah.');

        return redirect()->route('order.success', ['kode' => $this->orderCode]);
    }

    public function render()
    {
        return view('customer.orders.upload-proof', [
            'pembayaran' => $this->pembayaran,
            'pesanan' => $this->pesanan,
        ]);
    }
}
