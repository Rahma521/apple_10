<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users'],
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'phone' => ['nullable'],
            'zip_code' => ['nullable'],
            'city_id' => ['nullable', 'exists:cities'],
            'district' => ['nullable'],
            'short_address' => ['nullable'],
            'full_address' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
