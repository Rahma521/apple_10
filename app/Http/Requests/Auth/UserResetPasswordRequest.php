<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;

class UserResetPasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
           // 'email' => ['required', 'exists:users,email'],
            'code' => ['required', 'exists:users,code'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
        ];
    }
}
