<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // input field 'image' per catatan: required image types jpeg,png,webp, max ~2MB
            'image' => 'required|file|image|mimes:jpeg,png,webp|max:2048',
            'sort_order' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Gambar produk wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Tipe gambar tidak didukung. Gunakan jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'gambar produk',
            'sort_order' => 'urutan gambar',
        ];
    }
}