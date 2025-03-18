<?php


use App\Http\Controllers\Admin\{Auth\AuthController,
    BrandController,
    CategoryController,
    ContactFormController,
    OfferController,
    OrderController,
    OrganizationController,
    PaymentController,
    ProductController,
    Setting\ColorController,
    UserController};
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Setting\{CityController,
    CourseController,
    EducationLevelController,
    InstructorTypeController,
    RegionController,
    SettingController,
    StaticPageController};
use App\Http\Controllers\Admin\Setting\ServiceController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['CheckAdmin'])->group(function () {

    Route::apiResource('admins', AdminController::class);
    Route::apiResource('users', Usercontroller::class);

    Route::apiResource('cities', CityController::class);
    Route::apiResource('regions', RegionController::class);
    Route::get('regions/cities/{region}', [CityController::class,'cityByRegion']);
    Route::apiResource('instructor-types', InstructorTypeController::class);
    Route::apiResource('education-levels', EducationLevelController::class);

    Route::apiResource('brands', BrandController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::get('main-categories', [CategoryController::class, 'mainCategories']);
    Route::get('sub-categories/{category}', [CategoryController::class, 'subCategories']);

    Route::apiResource('organizations', OrganizationController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('offers', OfferController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('payments', PaymentController::class);



    Route::get('assigned-organization/{organization}/products', [OrganizationController::class,'getAssignedOrganization']);

    Route::get('organization/{organization}/products-with-discount', [OrganizationController::class,'getAssignedOrganizationWithDiscount']);

    Route::post('update-discount/{organization}', [OrganizationController::class,'updateDiscountForAssignedProduct']);

    Route::get('assigned-product/{product}/organizations', [ProductController::class,'getAssignedProducts']);
    Route::post('assign-product/{product}/organizations', [ProductController::class,'assignProducts']);
    Route::post('unassign-product/{product}/organizations', [ProductController::class,'unassignProducts']);

    Route::apiResource('colors', ColorController::class);
    Route::post('courses', [CourseController::class,'storeCourse']);
    Route::post('courses/{course}', [CourseController::class,'updateCourse']);

    Route::apiResource('courses', CourseController::class)->except('store', 'update');
    Route::apiResource('services', ServiceController::class);
   // Route::apiResource('static-pages', StaticPageController::class);
    Route::post('static-pages/{key}', [StaticPageController::class,'updatePageByKey']);

   // Route::post('products/{product}/colors/{color}/images', [ProductController::class,'uploadColorImages']);
    Route::group(['prefix' => 'contact-forms'], function () {
            Route::get('/', [ContactFormController::class, 'index']);
            Route::get('/{id}', [ContactFormController::class, 'show']);
            Route::delete('/{id}', [ContactFormController::class, 'destroy']);
        });

    Route::group(['prefix' => 'lists'], function () {
        Route::get('organizations', [settingController::class, 'getOrganizations']);
      //  Route::get('paymentMethods', [settingController::class, 'getPaymentMethods']);
        Route::get('OrderStatus', [settingController::class, 'getOrderStatus']);
        Route::get('products', [settingController::class, 'getProducts']);
        Route::get('organization-products/{organization}', [settingController::class,'getOrganizationProducts']);
    });
});


