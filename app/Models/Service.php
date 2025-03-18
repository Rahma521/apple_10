<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasTranslations, FilterableTrait, InteractsWithMedia;

    protected $fillable = [
        'name',
        'desc',
        'page'
    ];

    public array $translatable = [
        'name',
        'desc'
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'desc' => 'array',
        ];
    }
}
