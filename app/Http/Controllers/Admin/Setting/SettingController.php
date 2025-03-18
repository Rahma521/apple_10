<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Enums\ContactFormTypes;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\lists\minimalListResource;
use App\Http\Resources\lists\SettingListResource;
use App\Models\Organization;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ResponseTrait;

    public function getUserTypes()
    {
        return self::successResponse(data: SettingListResource::collection(UserTypeEnum::cases()));
    }

    public function getContactFormTypes()
    {
        return self::successResponse(data: SettingListResource::collection(ContactFormTypes::cases()));
    }

    public function getOrganizations()
    {
        return self::successResponse(data: minimalListResource::collection(Organization::all()));
    }

    public function getPaymentMethods()
    {
        return self::successResponse(data: SettingListResource::collection(PaymentMethodEnum::cases()));
    }

    public function getOrderStatus()
    {
        return self::successResponse(data: SettingListResource::collection(OrderStatusEnum::cases()));
    }

    public function getOrganizationProducts(Request $request, Organization $organization, Product $product)
    {
        $products = $organization->products()->get();
        if ($products->isEmpty()) {
            return self::successResponse(message: __('application.no_products_assigned'));
        }
        return self::successResponse(data: minimalListResource::collection($products));
    }

    public function getProducts()
    {
        return self::successResponse(data: minimalListResource::collection(Product::all()));
    }

}
