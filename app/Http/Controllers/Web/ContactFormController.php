<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Resources\ContactFormResource;
use App\Models\ContactForm;

class ContactFormController extends dashboardController
{
    public function __construct()
    {
        $this->model = ContactForm::class;
        $this->resourceClass = ContactFormResource::class;

        $this->storeRequestClass = new ContactFormRequest();
    }

}
