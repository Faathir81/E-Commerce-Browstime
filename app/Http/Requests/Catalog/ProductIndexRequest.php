<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public
    }

    public function rules(): array
    {
        return [
            'q'           => ['nullable','string','max:100'],
            'category_id' => ['nullable','integer','exists:categories,id'],
            'per_page'    => ['nullable','integer','min:1','max:100'],
            'sort'        => ['nullable','in:newest,price_asc,price_desc,name'],
        ];
    }
}
