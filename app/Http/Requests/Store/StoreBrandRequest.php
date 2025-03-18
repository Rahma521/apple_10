<?php

namespace App\Http\Requests\Store;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [

            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique('brands', 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique('brands', 'name->en'),
            ],
            'files'  => 'required|array|min:1',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }

}
