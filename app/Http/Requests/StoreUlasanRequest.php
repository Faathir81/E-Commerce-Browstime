<?php

namespace App\Http\Requests;

use App\Models\DetailPesanan;
use App\Support\ReviewGuard;
use Illuminate\Foundation\Http\FormRequest;

class StoreUlasanRequest extends FormRequest
{
    protected ?DetailPesanan $detailPesanan = null;

    public function authorize(): bool
    {
        $detail = $this->detailPesanan();
        if (! $detail) {
            return false;
        }

        return ReviewGuard::canReviewDetail(
            $detail,
            $this->user(),
            $this->input('guest_email')
        );
    }

    public function rules(): array
    {
        $rules = [
            'detail_pesanan_id' => ['required', 'integer', 'exists:detail_pesanans,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ];

        $rules['guest_email'] = $this->user()
            ? ['nullable', 'email']
            : ['required', 'email'];

        return $rules;
    }

    public function detailPesanan(): ?DetailPesanan
    {
        if ($this->detailPesanan === null) {
            $detailId = $this->input('detail_pesanan_id');
            $this->detailPesanan = $detailId
                ? DetailPesanan::with(['pesanan', 'ulasan'])->find($detailId)
                : null;
        }

        return $this->detailPesanan;
    }
}
