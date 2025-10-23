<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentCreateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_code' => ['required','string','max:50'],
            'provider'   => ['required','in:manual,midtrans'],
            // manual: method=transfer|qris
            // midtrans: method=qris|bank_transfer|gopay
            'method'     => ['required','string','in:transfer,qris,bank_transfer,gopay'],
            'amount'     => ['nullable','integer','min:0'],
        ];
    }
}
