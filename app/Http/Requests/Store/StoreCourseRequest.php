<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => [
                'required', 'string', Rule::unique('courses', 'name->ar'),
            ],
            'name.en' => [
                'required', 'string', Rule::unique('courses', 'name->en'),
            ],

            'desc' => ['required', 'array'],
            'desc.*' => ['required', 'string'],

            'url' => ['nullable'],
            'course'  => 'required|array|min:1',
            'course.video' => 'required|mimes:mp4,mov,wmv,avi,gif,webp|max:10000',
            'course.image' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',

        ];
    }
}
