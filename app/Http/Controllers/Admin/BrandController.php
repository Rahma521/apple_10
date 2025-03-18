<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Controllers\Controller;

use App\Http\Requests\Store\StoreBrandRequest;
use App\Http\Requests\Update\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;


class BrandController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = BrandResource::class;
        $this->model = Brand::class;
        $this->storeRequestClass = new StoreBrandRequest();
        $this->updateRequestClass = new UpdateBrandRequest();
        $this->relations = ['categories',];
        $this->useFilter = true;
    }

}
