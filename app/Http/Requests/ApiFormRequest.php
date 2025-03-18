<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiFormRequest extends FormRequest   
{
    use ResponseTrait;


    abstract public function rules();

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(
            $this->failResponse(422, $validator->errors()->first())
        );
    }
}
