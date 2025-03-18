<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'education_level_id' => ['required', 'exists:education_levels,id'],
            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique('organizations', 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique('organizations', 'name->en'),
            ],
            'domain' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!str_starts_with($value, '@')) {
                        $fail('The ' . $attribute . ' must start with @');
                    }
                },
                'unique:organizations,domain', // Replace 'domains' with your table name
            ],
            'delivery_price' => ['required', 'numeric'],
            'max_order' => ['required', 'integer'],
            'address' => ['required'],
            'files' => 'required|array|size:4', // Ensures exactly 4 keys in the array
            'files.main_banner' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.logo' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.dashboard_logo' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.login_logo' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }
}
