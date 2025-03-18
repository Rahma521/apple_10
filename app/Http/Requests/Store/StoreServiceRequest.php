<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique('services', 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique('services', 'name->en'),
            ],
            'desc' => ['required', 'array'],
            'desc.*' => ['string'],
            'page' => ['required', 'string', Rule::in(['students', 'education'])],
            'files'  => 'required|array|min:1',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }
}
