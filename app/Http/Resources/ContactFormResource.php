<?php

namespace App\Http\Resources;

use App\Models\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ContactForm */
class ContactFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->label(),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'instructor_type' => $this->instructorType->name,
            'message' => $this->message ?? '',
            'institution' => $this->institution ?? '',
        ];
    }
}
