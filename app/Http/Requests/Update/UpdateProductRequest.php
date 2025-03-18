<?php

namespace App\Http\Requests\Update;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'part_number' =>['nullable', 'string'],
            'upc_ean' => ['nullable', 'string'],
            'title' => ['nullable', 'array'],
            'title.ar' => [
                'nullable', 'string', Rule::unique('products', 'title->ar')->ignore($this->product->id),
            ],
            'title.en' => [
                'nullable', 'string', Rule::unique('products', 'title->en')->ignore($this->product->id),
            ],

            'sub_title' => ['nullable', 'array'],
            'sub_title.*' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'array'],
            'description.*' => ['nullable', 'string'],

            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string'],

            'legal' => ['nullable', 'array'],
            'legal.*' => ['nullable', 'string'],

            'specifications' => ['nullable', 'array'],
            'specifications.*' => ['nullable', 'string'],

            'technical_specifications' => ['nullable', 'array'],
            'technical_specifications.*' => ['nullable', 'string'],

            'price' => ['nullable', 'numeric', 'min:0'],
            'sub_category_id' => ['nullable', 'exists:categories,id'],

            'available' => ['nullable', 'boolean'],
            'visible' => ['nullable', 'boolean'],
            'color_id' => ['nullable', 'integer','exists:colors,id'],

            'files' => 'nullable|array',
            'files.product_banner' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',

            'color' => 'nullable|array',
            'color.color_img.*' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }

}
