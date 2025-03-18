<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\InstructorTypeRequest;
use App\Http\Requests\Store\StoreBaseRequest;
use App\Http\Requests\Update\UpdateBaseRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\InstructorTypeResource;
use App\Models\InstructorType;


class InstructorTypeController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = BaseResource::class;
        $this->model = InstructorType::class;
        $this->storeRequestClass = new StoreBaseRequest();
        $this->updateRequestClass = new UpdateBaseRequest();
        $this->useFilter = true;

    }

}
