<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // protect via route middleware (auth)
    }

    public function rules(): array
    {
        $productId = (int) $this->route('product');

        return [
            'name'           => ['sometimes', 'string', 'max:150'],
            'slug'           => [
                'sometimes',
                'string',
                'max:180',
                'alpha_dash',
                Rule::unique('products', 'slug')
                    ->ignore($productId)
                    ->whereNull('deleted_at'),
            ],
            'short_label'    => ['sometimes', 'nullable', 'string', 'max:50'],
            'description'    => ['sometimes', 'nullable', 'string'],
            'price'          => ['sometimes', 'numeric', 'min:0'],
            'estimated_days' => ['sometimes', 'integer', 'min:1'],
            'is_best_seller' => ['sometimes', 'boolean'],
            'is_active'      => ['sometimes', 'boolean'],

            'categories'     => ['sometimes', 'array'],
            'categories.*'   => ['integer', 'distinct', 'exists:categories,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $mapBool = static fn ($v) => filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $payload = $this->all();
        if ($this->has('is_best_seller')) {
            $payload['is_best_seller'] = $mapBool($this->input('is_best_seller'));
        }
        if ($this->has('is_active')) {
            $payload['is_active'] = $mapBool($this->input('is_active'));
        }
        $this->replace($payload);
    }

    public function attributes(): array
    {
        return [
            'short_label'    => 'label singkat',
            'estimated_days' => 'estimasi hari',
        ];
    }
}
