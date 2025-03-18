<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'integer'],
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'digits:10'],
            'instructor_type_id' => ['required', 'exists:instructor_types,id'],
            'message' => ['required'],
            'institution' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
