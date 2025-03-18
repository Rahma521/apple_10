<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ColorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'name' =>(string) $this->name,
            'code' => $this->code,
            'has_products' => $this->hasProducts(),
        ];
    }

}
