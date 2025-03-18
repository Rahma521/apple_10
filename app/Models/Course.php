<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Course extends Model implements HasMedia
{
    use HasTranslations,FilterableTrait, InteractsWithMedia;

    protected $table = 'courses';

    public array $translatable = ['name', 'desc'];

    protected $fillable = [
        'name',
        'desc',
        'url',
    ];

    public $timestamps = true;


    protected function casts(): array
    {
        return [
            'name' => 'array',
            'desc' => 'array',
        ];
    }
    protected $hidden = ['create_at', 'updated_at'];

    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
            'search_key' => 'search',
        ],
    ];

}
