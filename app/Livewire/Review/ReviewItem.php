<?php

namespace App\Livewire\Review;

use App\Models\DetailPesanan;
use App\Models\Ulasan;
use App\Support\ReviewGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ReviewItem extends Component
{
    #[Locked]
    public int $detailId;

    #[Locked]
    public string $productName;

    #[Locked]
    public int $qty;

    public bool $canReview = false;
    public bool $hasReview = false;
    public ?string $guestEmailForReview = null;

    public ?int $rating = null;
    public ?string $komentar = null;
    public ?string $successMessage = null;
    public ?int $reviewRating = null;
    public ?string $reviewKomentar = null;

    public function mount(DetailPesanan $detail, bool $canReview, bool $hasReview, ?string $guestEmailForReview = null): void
    {
        $this->detailId = $detail->id;
        $this->productName = $detail->produk->nama ?? 'Produk';
        $this->qty = $detail->qty;
        $this->canReview = $canReview;
        $this->hasReview = $hasReview;
        $this->guestEmailForReview = $guestEmailForReview;

        if ($hasReview && $detail->relationLoaded('ulasan') && $detail->ulasan) {
            $this->reviewRating = (int) $detail->ulasan->rating;
            $this->reviewKomentar = $detail->ulasan->komentar;
        }
    }

    public function submit(): void
    {
        $guestEmail = $this->guestEmailForReview ?? null;

        $this->validate($this->rules($guestEmail));

        $user = Auth::user();

        DB::transaction(function () use ($guestEmail, $user) {
            $detail = DetailPesanan::with(['pesanan', 'ulasan'])->lockForUpdate()->find($this->detailId);

            if (! $detail) {
                $this->addError('general', 'Item tidak ditemukan.');
                return;
            }

            if (! ReviewGuard::canReviewDetail($detail, $user, $guestEmail)) {
                $this->addError('general', 'Anda tidak diizinkan mengulas item ini.');
                return;
            }

            if ($detail->hasUlasan()) {
                $this->hasReview = true;
                $this->canReview = false;
                $this->addError('general', 'Item ini sudah memiliki ulasan.');
                return;
            }

            $pelanggan = ReviewGuard::resolveAuthorPelanggan($detail);
            if (! $pelanggan) {
                $this->addError('general', 'Data pelanggan tidak valid untuk ulasan.');
                return;
            }

            Ulasan::create([
                'pelanggan_id' => $pelanggan->id,
                'detail_pesanan_id' => $detail->id,
                'rating' => (int) $this->rating,
                'komentar' => $this->komentar,
            ]);

            $this->hasReview = true;
            $this->canReview = false;
            $this->successMessage = 'Terima kasih, ulasan Anda telah disimpan.';
            $this->reviewRating = (int) $this->rating;
            $this->reviewKomentar = $this->komentar;
        });

        $this->resetFormIfReviewed();
    }

    protected function resetFormIfReviewed(): void
    {
        if ($this->hasReview) {
            $this->rating = null;
            $this->komentar = null;
        }
    }

    protected function rules(?string $guestEmail): array
    {
        $rules = [
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ];

        $rules['guestEmailForReview'] = Auth::check()
            ? ['nullable', 'email']
            : ['required', 'email'];

        // ensure current value adheres
        $this->guestEmailForReview = $guestEmail;

        return $rules;
    }

    public function render()
    {
        return view('livewire.review.review-item');
    }
}
