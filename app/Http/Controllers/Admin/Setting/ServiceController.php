<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\Store\StoreServiceRequest;
use App\Http\Requests\Update\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;

class ServiceController extends dashboardController
{
    public function __construct()
    {
        $this->resourceClass = ServiceResource::class;
        $this->model = Service::class;
        $this->storeRequestClass = new StoreServiceRequest();
        $this->updateRequestClass = new UpdateServiceRequest();
    }

    public function allServices()
    {
        return ServiceResource::collection(Service::all());
    }
}
