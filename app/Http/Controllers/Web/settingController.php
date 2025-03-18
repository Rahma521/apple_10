<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContactFormTypes;
use App\Enums\OfferBundleTypeEnum;
use App\Enums\OfferTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\lists\SettingListResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\RegionResource;
use App\Models\City;
use App\Models\EducationLevel;
use App\Models\InstructorType;
use App\Models\Organization;
use App\Models\Region;
use App\Traits\ResponseTrait;

class settingController extends Controller
{
    use ResponseTrait;
    public function getUserTypes()
    {
        return self::successResponse(data: SettingListResource::collection(UserTypeEnum::cases()));
    }

    public function getOfferTypes()
    {
        return self::successResponse(data: SettingListResource::collection(OfferTypeEnum::cases()));
    }
    public function getOfferBundleType()
    {
        return self::successResponse(data: SettingListResource::collection(OfferBundleTypeEnum::cases()));
    }

    public function getEducationLevels()
    {
        $educationLevels = EducationLevel::all();
        return self::successResponse(data:BaseResource ::collection($educationLevels));
    }

    public function getInstructorTypes()
    {
        $instructorTypes = InstructorType::all();
        return self::successResponse(data:BaseResource ::collection($instructorTypes));
    }

    public function getRegions()
    {
        $regions = Region::all();
        return self::successResponse(data:regionResource::collection($regions));
    }

    public function getOrganizationsByCity(City $city)
    {
        $organizations = Organization::whereCityId($city->id)->get();
        return self::successResponse(data:OrganizationResource ::collection($organizations));
    }

    public function cityByRegion(Region $region)
    {
        $cities = $region->cities;
        return self::successResponse(data:CityResource::collection($cities));
    }

    public function getPaymentMethods()
    {
        return self::successResponse(data: SettingListResource::collection(PaymentMethodEnum::cases()));
    }

    public function getContacts()
    {
        return self::successResponse(data:SettingListResource ::collection(ContactFormTypes::cases()));
    }

}
