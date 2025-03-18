<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Admin */
class AdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' =>(string) $this->phone,
            'access_token' =>(string) $this->access_token
        ];
    }
}
