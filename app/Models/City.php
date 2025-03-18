<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, Relations\HasMany};
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations, FilterableTrait;


    protected $casts =[
        'name' => 'array'
    ];
    public array $translatable = ['name'];
    protected array $filterableColumns = [
        [
            'columns' => ['name'],
            'type' => 'like',
            'search_key' => 'search',
        ],
    ];
    protected $fillable = [
        'name',
        'region_id',
    ];
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function users(): hasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasUsers(): bool
    {
        return $this->users()->count() > 0;
    }
    public function organization(): hasMany
    {
        return $this->hasMany(Organization::class);
    }

    public function hasOrganizations(): bool
    {
        return $this->organization()->count() > 0;
    }

    public function addresses(): hasMany
    {
        return $this->hasMany(Address::class);
    }

    public function hasAddresses(): bool
    {
        return $this->addresses()->count() > 0;
    }


}
