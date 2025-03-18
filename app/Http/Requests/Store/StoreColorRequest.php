<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;

class StoreColorRequest extends ApiFormRequest
{

    public function rules(): array
    {
       return [
           'name' => ['nullable', 'string', 'unique:colors,name'],
           'code' => ['required', 'string', 'unique:colors,code', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
       ];
    }
}
