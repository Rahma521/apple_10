<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'region_id' => ['nullable', 'exists:regions,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'name' => ['nullable', 'array'],
            'name.ar' => [
                'nullable',
                'string',
                Rule::unique('organizations', 'name->ar')->ignore($this->organization->id), // Ignore the current record
            ],
            'name.en' => [
                'nullable',
                'string',
                Rule::unique('organizations', 'name->en')->ignore($this->organization->id), // Ignore the current record
            ],
            'domain' => ['nullable'],
            'delivery_price' => ['nullable', 'numeric'],
            'max_order' => ['nullable', 'integer'],
            'address' => ['nullable'],
            'files' => 'nullable|array', // Ensures exactly 4 keys in the array
            'files.main_banner' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.logo' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.dashboard_logo' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.login_logo' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }
}
