<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = is_object($product) ? ($product->id ?? null) : $product;

        $slugRule = Rule::unique('products', 'slug');
        if ($productId !== null) {
            $slugRule = $slugRule->ignore($productId);
        }

        return [
            'name' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                $slugRule,
            ],
            'price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
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