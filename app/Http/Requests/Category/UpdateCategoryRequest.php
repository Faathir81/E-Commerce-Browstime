<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // protect via route middleware (auth)
    }

    public function rules(): array
    {
        $categoryId = (int) $this->route('category');

        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'slug' => [
                'sometimes',
                'string',
                'max:120',
                'alpha_dash',
                Rule::unique('categories', 'slug')
                    ->ignore($categoryId)
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama kategori',
            'slug' => 'slug kategori',
        ];
    }
}
