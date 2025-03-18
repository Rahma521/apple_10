<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\Store\StoreBaseRequest;
use App\Http\Requests\Update\UpdateBaseRequest;
use App\Http\Resources\BaseResource;
use App\Models\EducationLevel;


class EducationLevelController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = BaseResource::class;
        $this->model = EducationLevel::class;
        $this->storeRequestClass = new StoreBaseRequest();
        $this->updateRequestClass = new UpdateBaseRequest();
        $this->useFilter = true;

    }

}
