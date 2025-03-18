<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class PageRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page_count' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
