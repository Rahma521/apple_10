<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function rules($id): array
    {

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($id)->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', 'max:255', Rule::unique('admins')->ignore($id)->whereNull('deleted_at')],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('password')) {
            $this->merge([
                'password' => bcrypt($this->password),
            ]);
        }
    }


}
