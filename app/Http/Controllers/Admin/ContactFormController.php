<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\ContactFormRequest;
use App\Http\Resources\ContactFormResource;
use App\Models\ContactForm;

class ContactFormController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = ContactFormResource::class;
        $this->model = ContactForm::class;
        $this->storeRequestClass = new ContactFormRequest();

        $this->useFilter = true;
    }

}
