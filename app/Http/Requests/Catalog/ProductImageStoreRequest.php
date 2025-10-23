<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Bisa batasi ke admin saja via middleware/guard rute.
        return true;
    }

    public function rules(): array
    {
        return [
            'image'    => ['required','file','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'is_cover' => ['nullable','boolean'],
        ];
    }
}
