<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{

    use HasTranslations, FilterableTrait,InteractsWithMedia;

    public array $translatable = [
        'name',
    ];
    protected $fillable = [
        'name',
        'brand_id',
        'parent_id',
    ];
    protected $casts = [
        'name' => 'array',
    ];
    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
        ],
        [
            'columns' => ['brand.name'],
            'type' => 'like',
            'search_key' => 'brand_name',
        ],
        [
            'columns' => ['children.name'],
            'type' => 'like',
            'search_key' => 'children_name',
        ],
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'sub_category_id');
    }

    public function hasProducts(): bool
    {
        return $this->products()->count() > 0;
    }

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'desc' => 'array',
        ];
    }
}
