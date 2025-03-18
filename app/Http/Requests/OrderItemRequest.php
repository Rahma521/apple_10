<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders'],
            'product_id' => ['required', 'exists:products'],
            'offer_id' => ['required', 'exists:offers'],
            'item_type' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
