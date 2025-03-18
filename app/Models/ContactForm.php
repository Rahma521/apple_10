<?php

namespace App\Models;

use App\Enums\ContactFormTypes;
use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactForm extends Model
{
    use FilterableTrait;
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'instructor_type_id',
        'message',
        'institution',
    ];

    protected array $filterableColumns = [
        [
            'columns' => 'type',
            'type' => 'equals',
        ],

    ];

    protected $casts =[
        'type' => ContactFormTypes::class,
    ];

    public function instructorType(): BelongsTo
    {
        return $this->belongsTo(InstructorType::class);
    }
}
