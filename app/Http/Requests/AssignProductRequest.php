<?php

namespace App\Http\Requests;

class AssignProductRequest extends ApiFormRequest
{

    public function rules(): array
    {
       return [
           'organizations' => ['nullable', 'array'],
           'organizations.*.id' => ['exists:organizations,id'],
           'organizations.*.discount' =>['nullable', 'numeric', 'min:0'],
       ];
    }
}
