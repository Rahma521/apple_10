<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends  DashboardController
{

    public function __construct()
    {
        $this->resourceClass = UserResource::class;
        $this->model = User::class;
       // $this->updateRequestClass = new UpdateAdminRequest();
        $this->relations = ['organization'];
        $this->useFilter = true;

    }
}
