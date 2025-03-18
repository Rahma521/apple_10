<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    public function rules($id): array
    {
        return [
            'name' => ['sometimes', 'array'],
            'name.ar' => [
                'nullable',
                'string',
                Rule::unique('cities', 'name->ar')->ignore($id), // Ignore the current record
            ],
            'name.en' => [
                'nullable',
                'string',
                Rule::unique('cities', 'name->en')->ignore($id), // Ignore the current record
            ],
            'region_id' => ['sometimes', 'integer', 'exists:regions,id'],
        ];
    }

}
