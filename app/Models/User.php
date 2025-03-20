<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserTypeEnum;
use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Relations\BelongsTo, Relations\HasOne, SoftDeletes};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory,HasApiTokens, Notifiable,FilterableTrait, softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone',
        'city_id',
        'organization_id',
        'education_level_id',
        'instructor_type_id',
        'code',
        'email_verified_at'
    ];

    protected array $filterableColumns = [
        [
            'columns' => ['name', 'phone', 'email'],
            'type' => 'like',
            'search_key' => 'search',
        ],
        [
            'columns' => 'organization_id',
            'type' => 'equals',
        ],
        [
            'columns' => 'city_id',
            'type' => 'equals',
        ],
        [
            'columns' => 'created_at',
            'type' => 'range',
            'start_key' => 'start_date',
            'end_key' => 'end_date',
        ],

    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserTypeEnum::class,
           // 'instructor_type' => InstructorType::class,
           // 'education_level' => EducationLevel::class,
        ];
    }



    public function instructorType(): BelongsTo
    {
        return $this->belongsTo(InstructorType::class);
    }
    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }


    public function countCartItems(): int
    {
        return $this->cart ? $this->cart->cartItems()->count() : 0;
    }
}
