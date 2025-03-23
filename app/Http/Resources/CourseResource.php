<?php

namespace App\Http\Resources;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Course */
class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'url' => $this->url,
            'video' => $this->getFirstMediaUrl('video'),
            'image' => $this->getFirstMediaUrl('image'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'translations' => $this->translations
        ];
    }
}
