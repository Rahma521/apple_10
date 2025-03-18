<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Resources\ProductDetailsResource;
use App\Models\Region;
use App\Http\Requests\{Store\StoreCityRequest, Update\UpdateCityRequest};
use App\Http\Resources\CityResource;
use App\Models\City;


class CityController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = CityResource::class;
        $this->model = City::class;
        $this->storeRequestClass = new StoreCityRequest();
        $this->updateRequestClass = new UpdateCityRequest();
        $this->relations = ['region'];
        $this->useFilter = true;
    }

    public function cityByRegion(Region $region)
    {
        $cities = $region->cities;
        return self::successResponse(data:CityResource::collection($cities));
    }

}
