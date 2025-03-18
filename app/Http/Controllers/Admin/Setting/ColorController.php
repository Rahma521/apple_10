<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\Store\StoreColorRequest;
use App\Http\Requests\Update\UpdateColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;


class ColorController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = ColorResource::class;
        $this->model = Color::class;
        $this->storeRequestClass = new StoreColorRequest();
        $this->updateRequestClass = new UpdateColorRequest();
    }



}
