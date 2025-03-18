<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasTranslations, FilterableTrait, InteractsWithMedia;

    public array $translatable = [
        'title',
        'sub_title',
        'description',
        'features',
        'legal',
        'specifications',
        'technical_specifications',
    ];

    protected $fillable = [
        'part_number',
        'upc_ean',
        'title',
        'sub_title',
        'description',
        'features',
        'legal',
        'specifications',
        'technical_specifications',
        'price',
        'sub_category_id',
        'available',
        'visible',
        'color_id',
    ];

    protected array $filterableColumns = [
        [
            'columns' => ['title', 'sub_title', 'description', 'features', 'legal', 'specifications', 'technical_specifications'],
            'type' => 'like',
            'search_key' => 'search',
        ],
        [
            'columns' => 'sub_category_id',
            'type' => 'equals',
        ],
        [
            'columns' => ['subCategory.parent_id'],
            'type' => 'equals',
            'search_key' => 'main_category_id',

        ]
    ];
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class,'sub_category_id');
    }

    public function colors(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_products', 'product_id', 'offer_id');
    }

    public function hasOffers(): bool
    {
        return $this->offers()->count() > 0;
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function hasOrders(): bool
    {
        return $this->orderItems()->count() > 0;
    }

    public function assignedOrganizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_product', 'product_id', 'organization_id')
            ->withTimestamps();
    }
    public function getDiscountForOrganization($organizationId)
    {
        $organizationProduct = $this->assignedOrganizations()
            ->where('organization_id', $organizationId)
            ->withPivot('discount')
            ->first();

        if ($organizationProduct &&
            $organizationProduct->pivot &&
            isset($organizationProduct->pivot->discount)) {
            return floatval($organizationProduct->pivot->discount);
        }

        return null;
    }
}
