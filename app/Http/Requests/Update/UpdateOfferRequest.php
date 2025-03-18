<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'array'],
            'title.ar' => [
                'nullable', 'string', Rule::unique('offers', 'title->ar')->ignore($this->offer->id),
            ],
            'title.en' => [
                'nullable', 'string', Rule::unique('offers', 'title->en')->ignore($this->offer->id),
            ],

            'brief' => ['nullable', 'array'],
            'brief.*' => ['nullable', 'string'],

            'desc' => ['nullable', 'array'],
            'desc.*' => ['nullable', 'string'],

            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],

            'status' => ['nullable', 'integer','in:2,1'],
            'type' => ['nullable', 'integer','in:1,2'],
            'bundle_type' => ['nullable', 'integer','in:1,2'],
            'percent' => ['nullable', 'numeric'],
            'organization_id' => ['nullable', 'exists:organizations,id'],

            'products' => ['nullable', 'array'],
            'products.*.id' => ['exists:products,id'],
            'products.*.discount' =>['nullable', 'numeric', 'min:0'],

            'files' => 'nullable|array',
            'files.offer_sub.*' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.offer_main' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',

        ];
    }
}
