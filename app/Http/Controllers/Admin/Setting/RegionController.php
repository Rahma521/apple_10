<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\RegionRequest;
use App\Http\Requests\Store\StoreBaseRequest;
use App\Http\Requests\Update\UpdateBaseRequest;
use App\Http\Resources\RegionResource;
use App\Models\Region;


class RegionController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = RegionResource::class;
        $this->model = Region::class;
        $this->storeRequestClass = new StoreBaseRequest();
        $this->updateRequestClass = new UpdateBaseRequest();
        $this->useFilter = true;

    }

}
