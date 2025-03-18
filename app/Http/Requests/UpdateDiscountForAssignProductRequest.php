<?php

namespace App\Http\Requests;

class UpdateDiscountForAssignProductRequest extends ApiFormRequest
{

    public function rules(): array
    {
        return [
            'discount' => ['required', 'numeric', 'min:0'], // Single discount value
            'products' => ['required', 'array'],
            'products.*' => ['exists:organization_product,product_id'],
        ];
    }

}
