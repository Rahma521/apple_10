<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\{Model, Relations\HasMany};
use Spatie\Translatable\HasTranslations;

class InstructorType extends Model
{
    use HasTranslations, FilterableTrait;
    protected $table = 'instructor_types';

    public array $translatable = ['name'];
    protected $fillable = [
        'name',
    ];
    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
            'search_key' => 'search',
        ],
    ];
    protected $casts = [
        'name' => 'array'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasUsers(): bool
    {
        return $this->users()->count() > 0;
    }
}
