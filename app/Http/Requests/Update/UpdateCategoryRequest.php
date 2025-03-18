<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function rules($id): array
    {
        return [
            'name' => ['nullable', 'array'],
            'name.ar' => [
                'nullable',
                'string',
                Rule::unique('categories', 'name->ar')->ignore($id), // Ignore the current record
            ],
            'name.en' => [
                'nullable',
                'string',
                Rule::unique('categories', 'name->en')->ignore($id), // Ignore the current record
            ],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'files'  => 'nullable|array|min:1',
            'files.*' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }


}
