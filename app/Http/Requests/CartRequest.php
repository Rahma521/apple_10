<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'item_type' => ['required','string','in:product,offer'],
            'product_id' => ['nullable', 'exists:products,id'],
            'offer_id' => ['nullable', 'exists:offers,id'],
           // 'quantity' => ['required', 'numeric'],
           // 'cart_items' => ['required', 'array'],
    //        'cart_items.*.item_type' => ['nullable', 'integer'],
    //        'cart_items.*.product_id' => ['nullable', 'exists:products'],
    //        'cart_items.*.offer_id' => ['nullable', 'exists:offers'],
           // 'cart_items.*.price' => ['nullable', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
