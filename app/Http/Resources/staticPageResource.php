<?php

namespace App\Http\Resources;

use App\Models\staticPage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin staticPage */
class staticPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'hero_main_title' => $this->hero_main_title,
            'hero_desc' => $this->hero_desc,
            'hero_img' => $this->getFirstMediaUrl('hero'),

            'sec_desc' => $this->sec_desc,
            'sec_img' => $this->getFirstMediaUrl('sec'),

            'third_desc' => $this->third_desc,
            'third_img' => $this->getFirstMediaUrl('third'),

            'end_desc' => $this->end_desc,
            'end_img' => $this->getFirstMediaUrl('end'),

          //  'translations' => $this->translations
        ];
    }
}
