<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class staticPage extends Model implements HasMedia
{
    use  InteractsWithMedia;

    protected $fillable = [
        'key',
        'hero_img',
        'hero_main_title',
        'hero_title',
        'hero_desc',
        'sec_title',
        'sec_desc',
        'third_title',
        'third_desc',
        'end_title',
        'end_desc',
    ];

    protected function casts(): array
    {
        return [
            'hero_main_title' => 'array',
            'hero_title' => 'array',
            'hero_desc' => 'array',
            'sec_title' => 'array',
            'sec_desc' => 'array',
            'third_title' => 'array',
            'third_desc' => 'array',
            'end_title' => 'array',
            'end_desc' => 'array',
        ];
    }
}
