<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Resources\PaymentTransactionResource;
use App\Models\PaymentTransaction;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PaymentController extends DashboardController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->resourceClass = PaymentTransactionResource::class;
        $this->model = PaymentTransaction::class;
        $this->relations = ['order'];
        $this->useFilter = true;
    }

}
