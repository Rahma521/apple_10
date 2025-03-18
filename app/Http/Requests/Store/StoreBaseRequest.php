<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBaseRequest extends FormRequest
{
    public function rules(?string $table = null): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique($table, 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique($table, 'name->en'),
            ],
        ];
    }

}
