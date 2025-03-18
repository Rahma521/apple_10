<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\{Model,Relations\HasMany};
use Spatie\Translatable\HasTranslations;


class Region extends Model
{
    use HasTranslations, FilterableTrait;


    protected $fillable = [
        'name',
    ];
    public array $translatable = ['name'];

    protected $casts =[
        'name' => 'array'
    ];
    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
            'search_key' => 'search',
        ],
    ];
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function hasCities(): bool
    {
        return $this->cities()->count() > 0;
    }
}
