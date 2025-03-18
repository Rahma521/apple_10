<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function rules($id): array
    {
        return [
            'name' => ['nullable', 'array'],
            'name.ar' => [
                'nullable', 'string', Rule::unique('services', 'name->ar')->ignore($id),
            ],
            'name.en' => [
                'nullable', 'string', Rule::unique('services', 'name->en')->ignore($id),
            ],
            'desc' => ['nullable', 'array'],
            'desc.*' => ['string'],
            'page' => ['nullable', 'string', Rule::in(['students', 'education'])],
            'files'  => 'nullable|array|min:1',
            'files.*' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }

}
