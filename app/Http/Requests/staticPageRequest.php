<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class staticPageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => ['nullable'],

            'images' => 'nullable|array',
            'images.hero' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',

            'hero_main_title' => ['nullable','array'],
            'hero_main_title.ar' => ['nullable','string'],
            'hero_main_title.en' => ['nullable','string'],

            'hero_desc' => ['nullable','array'],
            'hero_desc.ar' => ['nullable','string'],
            'hero_desc.en' => ['nullable','string'],

            'sec_desc' => ['nullable','array'],
            'sec_desc.ar' => ['nullable','string'],
            'sec_desc.en' => ['nullable','string'],
            'images.sec' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',

            'third_desc' => ['nullable','array'],
            'third_desc.ar' => ['nullable','string'],
            'third_desc.en' => ['nullable','string'],
            'images.third' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',

            'end_desc' => ['nullable','array'],
            'end_desc.ar' => ['nullable','string'],
            'end_desc.en' => ['nullable','string'],
            'images.end' => 'nullable|mimes:jpeg,jpg,png,gif,webp|max:10000',

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
