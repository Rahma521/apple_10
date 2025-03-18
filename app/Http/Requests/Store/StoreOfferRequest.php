<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.ar' => [
                'required', 'string', Rule::unique('offers', 'title->ar'),
            ],
            'title.en' => [
                'required', 'string', Rule::unique('offers', 'title->en'),
            ],
            'brief' => ['nullable', 'array'],
            'brief.*' => ['nullable', 'string'],

            'desc' => ['nullable', 'array'],
            'desc.*' => ['nullable', 'string'],

            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],

            'status' => ['nullable', 'integer','in:2,1'],
            'type' => ['required', 'integer','in:1,2'],
            'bundle_type' => ['required_if:type,2', 'integer','in:1,2'],

            'percent' => ['required', 'numeric'],
            'organization_id' => ['required', 'exists:organizations,id'],

            'products' => ['required', 'array'],

            'products.*.id' => ['exists:products,id'],
            'products.*.discount' =>['nullable', 'numeric', 'min:0'],


            'files' => 'required|array',
            'files.offer_sub.*' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
            'files.offer_main' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',

        ];
    }
}
