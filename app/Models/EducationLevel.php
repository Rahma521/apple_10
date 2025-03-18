<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class EducationLevel extends Model
{
    use HasTranslations, FilterableTrait;

    protected $table = 'education_levels';


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
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasUsers(): bool
    {
        return $this->users()->count() > 0;
    }
}
