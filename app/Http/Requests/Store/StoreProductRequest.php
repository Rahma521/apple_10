<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'part_number' =>['required', 'string'],
            'upc_ean' => ['required', 'string'],
            'title' => ['required', 'array'],
            'title.ar' => [
                'required', 'string', Rule::unique('products', 'title->ar'),
            ],
            'title.en' => [
                'required', 'string', Rule::unique('products', 'title->en'),
            ],

            'sub_title' => ['nullable', 'array'],
            'sub_title.*' => ['nullable', 'string', 'max:255'],

            'description' => ['required', 'array'],
            'description.*' => ['required', 'string'],

            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],

            'legal' => ['required', 'array'],
            'legal.*' => ['required', 'string'],

            'specifications' => ['required', 'array'],
            'specifications.*' => ['required', 'string'],

            'technical_specifications' => ['nullable', 'array'],
            'technical_specifications.*' => ['nullable', 'string'],

            'price' => ['required', 'numeric', 'min:0'],
            'sub_category_id' => ['required', 'exists:categories,id'],

            'available' => ['required', 'boolean'],
            'visible' => ['required', 'boolean'],
            'color_id' => ['nullable', 'integer','exists:colors,id'],

            'files' => 'required|array',
            'files.product_banner' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',

            'color' => 'required|array',
            'color.color_img.*' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }
}
