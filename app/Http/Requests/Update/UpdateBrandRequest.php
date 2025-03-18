<?php

namespace App\Http\Requests\Update;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends formRequest
{
    public function rules($id): array
    {
        return [
            'name' => ['nullable', 'array'],
            'name.ar' => [
                'nullable',
                'string',
                Rule::unique('brands', 'name->ar')->ignore($id), // Ignore the current record
            ],
            'name.en' => [
                'nullable',
                'string',
                Rule::unique('brands', 'name->en')->ignore($id), // Ignore the current record
            ],
            'files'  => 'nullable|array|min:1',
            'files.*' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }

}
