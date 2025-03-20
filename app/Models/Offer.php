<?php

namespace App\Models;

use App\Enums\OfferBundleTypeEnum;
use App\Enums\OfferTypeEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Offer extends Model  implements HasMedia
{
    use HasTranslations, FilterableTrait, InteractsWithMedia;

    protected $fillable = [
        'title',
        'brief',
        'desc',
        'start_date',
        'end_date',
        'status',
        'type',
        'bundle_type',
        'percent',
        'price_before_discount',
        'price_after_discount',
        'organization_id',
    ];

    public $translatable = ['title', 'brief', 'desc'];

    public array $filterableColumns = [
        [
            'columns' => ['title'],
            'type' => 'like',
            'search_key' => 'search',
        ],
        [
            'columns' => ['organization_id'],
            'type' => 'equals',
        ]
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'brief' => 'array',
            'desc' => 'array',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'type' => OfferTypeEnum::class,
            'bundle_type' => OfferBundleTypeEnum::class,
            'status' =>StatusEnum::class
        ];
    }
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'offer_products', 'offer_id', 'product_id')
            ->withPivot('specific_discount')->withTimestamps();
    }

    public function offeredProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'offer_products', 'offer_id', 'product_id')
            ->withTimestamps();
    }

    public function getOfferPrice(): float
    {
        return $this->products()->sum('price') * $this->percent/100;
    }

    public function getPriceAfterDiscount(): float
    {
        return $this->products()->sum('price') - $this->getOfferPrice();
    }

    public function getPriceBeforeDiscount(): float
    {
        return $this->products()->sum('price');
    }


}
