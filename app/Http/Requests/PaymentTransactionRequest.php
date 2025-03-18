<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentTransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required'],
            'transaction_id' => ['required'],
            'order_id' => ['required', 'exists:orders'],
            'last_four_digits' => ['required'],
            'user_id' => ['required', 'exists:users'],
            'status' => ['boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
