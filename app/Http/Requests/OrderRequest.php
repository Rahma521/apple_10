<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            // Order basic data
            'payment_method' => 'required|in:1,2,3,4,5',
            'address_id' => [
                Rule::requiredIf(!$this->has('address')),
                'exists:addresses,id'
            ],

            // Optional address data
            'address' => 'array|nullable',
            'address.first_name' => [
                Rule::requiredIf($this->has('address')),
                'string',
                'max:255'
            ],
            'address.last_name' => [
                Rule::requiredIf($this->has('address')),
                'string',
                'max:255'
            ],
            'address.phone' => [
                Rule::requiredIf($this->has('address')),
                'string',
                'max:20'
            ],
            'address.zip_code' => [
                Rule::requiredIf(function () {
                    return $this->has('address') && $this->input('address.type') == 'home';
                }),
                'string',
                'max:10'
            ],
            'address.city_id' => [
                Rule::requiredIf(function () {
                    return $this->has('address') && $this->input('address.type') == 'home';
                }),
                'exists:cities,id'
            ],
            'address.district' => [
                Rule::requiredIf(function () {
                    return $this->has('address') && $this->input('address.type') == 'home';
                }),
                'string',
                'max:255'
            ],
            'address.short_address' => [
                Rule::requiredIf($this->has('address')),

                'string',
                'max:500'
            ],
            'address.full_address' => [
                Rule::requiredIf(function () {
                    return $this->has('address') && $this->input('address.type') == 'home';
                }),
                'string'
            ],
            'address.type' => [
                Rule::requiredIf($this->has('address')),
                'string',
                'in:home,organization'
            ]
        ];
    }

    public function messages()
    {
        return [
            'address_id.required_if' => 'Please provide either an existing address ID or new address details.',
            'payment_method.in' => 'The selected payment method is invalid.',
            'address.city_id.exists' => 'The selected city is invalid.'
        ];
    }
}
