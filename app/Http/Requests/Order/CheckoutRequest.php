<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'buyer_name'  => ['required','string','max:200'],
            'buyer_phone' => ['required','string','max:20'],
            'address'     => ['required','string','max:500'],
            'shipping_fee'=> ['nullable','integer','min:0'],
            'notes'       => ['nullable','string','max:300'],
        ];
    }
}
