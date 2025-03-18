<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'name' => $this->name,
            'has_users' => $this->hasUsers()??false,

            'translations' => $this->translations ?? [],
        ];
    }
}
