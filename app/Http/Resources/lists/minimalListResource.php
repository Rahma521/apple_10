<?php

namespace App\Http\Resources\lists;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class minimalListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name ? $this->name : $this->title,
        ];
    }
}
