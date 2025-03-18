<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Organization extends Model implements HasMedia
{
    use SoftDeletes,HasTranslations,FilterableTrait, InteractsWithMedia;

    protected $fillable = [
        'city_id',
        'education_level_id',
        'name',
        'domain',
        'delivery_price',
        'max_order',
        'address',
    ];

    public array $translatable = ['name'];

    public $timestamps = true;
    protected $hidden = ['create_at', 'updated_at','deleted_at'];

    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
            'search_key' => 'search',
        ],
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }
   public function visibleProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->whereVisible(1)->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function assignedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'organization_product', 'organization_id', 'product_id')
            ->withTimestamps();
    }

    public function assignedProductsCount(): int
    {
        return $this->assignedProducts()->count();
    }

    public function offers():HasMany
    {
        return $this->hasMany(Offer::class);
    }


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasUsers(): bool
    {
        return $this->users()->count() > 0;
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, User::class);
    }

    public function hasOrders(): bool
    {
        return $this->orders()->count() > 0;
    }


    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }
}
