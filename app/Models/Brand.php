<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Brand extends Model implements HasMedia
{
    use HasTranslations,FilterableTrait, InteractsWithMedia;

    public array $translatable = ['name'];

    protected $table = 'brands';
    protected $fillable = [
        'name',
    ];
    public $timestamps = true;
    protected $casts = [
        'name' => 'array',
    ];

    protected $hidden = ['create_at', 'updated_at'];

    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
            'search_key' => 'search',
        ],
    ];

    public function categories() :HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function hasCategories(): bool
    {
        return $this->categories()->count() > 0;
    }
}
