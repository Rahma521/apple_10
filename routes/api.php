<?php

use App\Http\Controllers\Admin\Setting\StaticPageController;
use App\Http\Controllers\Web\ContactFormController;
use App\Http\Middleware\SetOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'user'], function () {
    Route::post('register', [\App\Http\Controllers\Web\Auth\AuthController::class, 'register']);
    Route::post('verify', [\App\Http\Controllers\Web\Auth\AuthController::class, 'verify']);
    Route::post('login', [\App\Http\Controllers\Web\Auth\AuthController::class, 'login']);
    Route::post('reset-password', [\App\Http\Controllers\Web\Auth\AuthController::class, 'resetPassword']);
    Route::post('forgot-password', [\App\Http\Controllers\Web\Auth\AuthController::class, 'forgotPassword']);

    Route::post('contact-us/', [ContactFormController::class, 'store']);
});


Route::group(['prefix' => 'lists'], function () {
    Route::get('user-types', [\App\Http\Controllers\Web\settingController::class, 'getUserTypes']);

    Route::get('education-levels', [\App\Http\Controllers\Web\settingController::class, 'getEducationLevels']);
    Route::get('instructor-types', [\App\Http\Controllers\Web\settingController::class, 'getInstructorTypes']);

    Route::get('paymentMethods', [\App\Http\Controllers\Web\settingController::class, 'getPaymentMethods']);

    Route::get('offer-types', [\App\Http\Controllers\Web\settingController::class, 'getOfferTypes']);
    Route::get('offer-bundle-types', [\App\Http\Controllers\Web\settingController::class, 'getOfferBundleType']);

    Route::get('regions', [\App\Http\Controllers\Web\settingController::class, 'getRegions']);
    Route::get('organizations/{city}', [\App\Http\Controllers\Web\settingController::class, 'getOrganizationsByCity']);

    Route::get('cities/{region}',[\App\Http\Controllers\Web\settingController::class,'cityByRegion']);
    Route::get('contacts',[\App\Http\Controllers\Web\settingController::class,'getContacts']);
});

Route::middleware(['CheckUser'])->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('logout', [\App\Http\Controllers\Web\Auth\AuthController::class, 'logout']);
        Route::post('cart', [\App\Http\Controllers\Web\CartController::class, 'store']);
        Route::get('cart', [\App\Http\Controllers\Web\CartController::class, 'index']);
        Route::delete('cart-item/{cartItem}', [\App\Http\Controllers\Web\CartController::class, 'deleteItem']);
        Route::delete('all-cart-item/{cartItem}', [\App\Http\Controllers\Web\CartController::class, 'destroyItem']);

        Route::delete('cart/{cart}', [\App\Http\Controllers\Web\CartController::class, 'emptyCart']);
        Route::apiResource('order', \App\Http\Controllers\Web\OrderController::class);
        Route::post('order-payment/{order}', [\App\Http\Controllers\Web\OrderController::class, 'processPayment']);
    });

    Route::prefix('organization')->middleware(SetOrganization::class)->group(function () {

        Route::get('/', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'show']);
        Route::get('main-categories', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'getCategories']);
        Route::get('sub-categories/{category}', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'getSubCategories']);

        Route::get('products', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'getProducts']);
        Route::get('product/{product}', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'getProduct']);

        Route::get('offers', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'getOffers']);
        Route::get('offer/{offer}', [\App\Http\Controllers\Web\Organization\OrganizationController::class, 'getOffer']);

    });
});

//Route::get('cart-total/{cartId}', [\App\Http\Controllers\Web\CartController::class, 'calculatePrices']);

Route::get('static-pages', [StaticPageController::class,'getPagesKeys']);
Route::get('static-pages/{key}', [StaticPageController::class,'getPageByKey']);
