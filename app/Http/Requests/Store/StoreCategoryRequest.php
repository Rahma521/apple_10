<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique('categories', 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique('categories', 'name->en'),
            ],

            'brand_id' => ['required if:parent_id ,null', 'integer', 'exists:brands,id'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'files'  => 'required if:parent_id ,null|array|min:1',
            'files.*' => 'required if:parent_id ,null|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
