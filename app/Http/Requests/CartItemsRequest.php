<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => ['required', 'exists:carts'],
            'product_id' => ['required', 'exists:products'],
            'offer_id' => ['required', 'exists:offers'],
            'price' => ['nullable', 'numeric'],
            'item_type' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
