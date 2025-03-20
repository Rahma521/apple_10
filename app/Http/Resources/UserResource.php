<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' =>(string) $this->phone,
            'lang' =>(string) $this->lang,
            'organization_id' =>(int) $this->organization_id,
            'organization' =>(string) $this->organization?->name,
            'city_id' =>(int) $this->city_id,
            'city' =>(string) $this->city->name,
            'region_id' =>(int) $this->city?->region?->id,
            'region' =>(string) $this->city?->region?->name,
            'type_id' => $this->type instanceof UserTypeEnum ? $this->type->value : (int) $this->type,
            'type' => $this->type instanceof UserTypeEnum ? $this->type->label() : (is_int($this->type) ? UserTypeEnum::from($this->type)->label() : null),
            'instructor_type_id' =>(int) $this->instructor_type_id,
            'instructor_type' =>(string) $this->instructorType?->name,
           // 'education_level_id' =>(int) $this->education_level_id,
            //'education_level' =>(string) $this->educationLevel?->name,
            'code' =>(string) $this->code,
            'cart_items_count' => (int) $this->countCartItems(),
            'access_token' =>(string) $this->access_token
      ];
    }
}
