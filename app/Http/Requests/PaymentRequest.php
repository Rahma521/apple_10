<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
           // 'brand' => 'required|in:VISA,MASTER',
            'number' => 'required|string|min:16|max:16',
            'holder' => 'required|string|max:255',
            'expiry_month' => [
                'required',
                'string',
                'size:2',
                'regex:/^(0[1-9]|1[0-2])$/'
            ],
            'expiry_year' => [
                'required',
                'string',
                'size:4',
                'regex:/^20[2-9][0-9]$/'
            ],
            'cvv' => 'required|string|size:3|regex:/^[0-9]{3}$/'
        ];
    }

    public function messages()
    {
        return [
            'brand.in' => 'Only VISA and MASTER cards are accepted',
            'number.min' => 'Card number must be 16 digits',
            'number.max' => 'Card number must be 16 digits',
            'expiry_month.regex' => 'Invalid expiry month',
            'expiry_year.regex' => 'Invalid expiry year',
            'cvv.regex' => 'CVV must be 3 digits'
        ];
    }
}
