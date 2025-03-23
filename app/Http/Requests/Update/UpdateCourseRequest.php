<?php

namespace App\Http\Requests\Update;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'array'],
            'name.ar' => [
                'nullable', 'string', Rule::unique('courses', 'name->ar')->ignore($this->course->id),
            ],
            'name.en' => [
                'nullable', 'string', Rule::unique('courses', 'name->en')->ignore($this->course->id),
            ],

            'desc' => ['nullable', 'array'],
            'desc.*' => ['nullable', 'string'],

            'url' => ['nullable'],
            'course'  => 'nullable|array|min:1',
            'course.video' => 'nullable|mimes:mp4,mov,wmv,avi,gif,webp|max:10000',
            'course.image' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',
        ];
    }
}
