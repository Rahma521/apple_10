<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBaseRequest extends FormRequest
{
    public function rules($id,?string $table = null): array
    {
        return [
            'name' => ['nullable', 'array'],
            'name.ar' => [
                'nullable',
                'string',
                Rule::unique($table, 'name->ar')->ignore($id), // Ignore the current record
            ],
            'name.en' => [
                'nullable',
                'string',
                Rule::unique($table, 'name->en')->ignore($id), // Ignore the current record
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.ar.unique' => __('The Arabic name has already been taken.'),
            'name.en.unique' => __('The English name has already been taken.'),
        ];
    }
}
