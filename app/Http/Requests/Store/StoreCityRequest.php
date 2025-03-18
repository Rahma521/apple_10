<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique('cities', 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique('cities', 'name->en'),
            ],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
        ];
    }

}
