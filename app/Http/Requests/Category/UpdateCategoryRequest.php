<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category');
        $categoryId = is_object($category) ? ($category->id ?? null) : $category;

        $slugRule = Rule::unique('categories', 'slug');
        if ($categoryId !== null) {
            $slugRule = $slugRule->ignore($categoryId);
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
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama kategori',
            'slug' => 'slug',
        ];
    }
}