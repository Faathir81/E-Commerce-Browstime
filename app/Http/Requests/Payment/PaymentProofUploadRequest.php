<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentProofUploadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'proof' => ['required','file','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ];
    }
}
