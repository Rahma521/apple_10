<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Organization */
class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'name' => $this->name,
            'domain' =>(string) $this->domain,
            'delivery_price' =>(float) $this->delivery_price,
            'max_order' =>(int) $this->max_order,
            'address' =>(string) $this->address,
            'city_id' => $this->city_id,
            'city' =>$this->city->name,
            'region_id' =>$this->city?->region?->id,
            'region' =>$this->city?->region?->name,
            'education_level_id' => $this->education_level_id,
            'education_level' =>$this->educationLevel->name,
            'main_banner' => $this->getFirstMediaUrl('main_banner'),
            'logo' => $this->getFirstMediaUrl('logo'),
            'dashboard_logo' => $this->getFirstMediaUrl('dashboard_logo'),
            'login_logo' => $this->getFirstMediaUrl('login_logo'),
            'created_at' => $this->created_at,
            'has_users' => $this->hasUsers(),
            'has_orders' => $this->hasOrders(),
            'assigned_products_count' => $this->assignedProductsCount(),
            'offers_count' => $this->offers()->count(),
            'users_count' => $this->users()->count(),
            'orders_count' => $this->orders()->count(),
            'translations' => $this->translations ?? [],
        ];
    }
}
