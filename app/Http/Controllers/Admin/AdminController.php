<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\Store\StoreAdminRequest;
use App\Http\Requests\Update\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;

class AdminController extends DashboardController
{

    public function __construct()
    {
        $this->resourceClass = AdminResource::class;
        $this->model = Admin::class;
        $this->storeRequestClass = new StoreAdminRequest();
        $this->updateRequestClass = new UpdateAdminRequest();
      //  $this->relations = ['categories',];
        $this->useFilter = true;
    }


    public function updateAdmin(AdminRequest $request, Admin $admin)
    {
        $admin->update($request->validated());

        return new AdminResource($admin);
    }


}
