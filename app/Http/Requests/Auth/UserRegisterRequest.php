<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;
use function App\Http\Requests\Auth\User\convert2english;

class UserRegisterRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255', Rule::unique('users', 'name')->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', 'min:8', 'max:15', Rule::unique('users', 'phone')->whereNull('deleted_at')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'education_level_id' => ['required_if:type,2', 'integer', 'exists:education_levels,id'],
            'instructor_type_id' => ['required_if:type,1,3', 'integer', 'exists:instructor_types,id'],
            'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&#]/',
        ],
            'type' => ['required', 'integer', 'in:1,2,3'],
        ];
    }


    public function messages()
    {
        return [
            'education_level_id.required_if' => __('validation.required_if', [
                'other' => \App\Enums\UserTypeEnum::student->label()
            ]),

            'instructor_type_id.required_if' => __('validation.required_if', [
                'other' => implode(' / ', [
                    \App\Enums\UserTypeEnum::instructor->label(),
                    \App\Enums\UserTypeEnum::other->label(),
                ])
            ]),
        ];
    }


}
