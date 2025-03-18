<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;

class UserVerifyRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'exists:users,code', 'digits:6'],
        ];
    }
}
