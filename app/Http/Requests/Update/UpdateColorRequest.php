<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;

class UpdateColorRequest extends ApiFormRequest
{

    public function rules(): array
    {
       return [
           'name' => ['nullable', 'string', 'unique:colors,name'],
           'code' => ['nullable', 'string', 'unique:colors,code'],
       ];
    }
}
