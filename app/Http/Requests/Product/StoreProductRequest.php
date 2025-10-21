<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug'),
            ],
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            // other safe fields if present in DB can be provided as nullable
            'short_label' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
            'estimated_days' => 'sometimes|nullable|integer|min:0',
            'is_best_seller' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Slug sudah digunakan.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama produk',
            'slug' => 'slug produk',
            'price' => 'harga',
            'category_id' => 'kategori',
        ];
    }
}