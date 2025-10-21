<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // protect via route middleware (auth)
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:150'],
            'slug'           => [
                'required',
                'string',
                'max:180',
                'alpha_dash',
                Rule::unique('products', 'slug')->whereNull('deleted_at'),
            ],
            'short_label'    => ['nullable', 'string', 'max:50'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1'],
            'is_best_seller' => ['sometimes', 'boolean'],
            'is_active'      => ['sometimes', 'boolean'],

            // M:N categories via pivot product_categories
            'categories'     => ['sometimes', 'array'],
            'categories.*'   => ['integer', 'distinct', 'exists:categories,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_best_seller' => filter_var($this->input('is_best_seller', false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'is_active'      => filter_var($this->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        ]);
    }

    public function attributes(): array
    {
        return [
            'short_label'    => 'label singkat',
            'estimated_days' => 'estimasi hari',
        ];
    }
}
