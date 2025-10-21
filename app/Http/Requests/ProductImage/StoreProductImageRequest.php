<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // protect via route middleware (auth)
    }

    public function rules(): array
    {
        return [
            'image'      => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'image'      => 'gambar produk',
            'sort_order' => 'urutan',
        ];
    }
}
