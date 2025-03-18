<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class StoreAdminRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:155', Rule::unique('admins', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', Rule::unique('admins', 'phone')->whereNull('deleted_at')],
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
