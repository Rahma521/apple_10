<?php

namespace App\Http\Resources\lists;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->value,
            'name' => (string) $this->label(),
        ];
    }
}
