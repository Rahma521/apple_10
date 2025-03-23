<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\Store\StoreCategoryRequest;
use App\Http\Requests\Update\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = CategoryResource::class;
        $this->model = Category::class;
        $this->storeRequestClass = new StoreCategoryRequest();
        $this->updateRequestClass = new UpdateCategoryRequest();
        $this->relations = ['brand','parent','children','products'];
        $this->useFilter = true;
    }

    public function mainCategories(): JsonResponse
    {
        $categories = Category::with('brand')->whereNull('parent_id')->get();
        return self::successResponsePaginate(data: CategoryResource::collection($categories)->response()->getData(true));

    }

    public function subCategories(Category $category): JsonResponse
    {
        $categories = Category::with('products','parent')->where('parent_id',$category->id)->get();
        return self::successResponse(data:SubCategoryResource::collection($categories));
    }
}
