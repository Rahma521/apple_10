<?php

namespace App\Http\Controllers\Web\Organization;

use App\Enums\OfferTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OfferDetailsResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Organization;
use App\Models\OrganizationProduct;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    use ResponseTrait;

    public function show(Request $request)
    {
        $organization = Organization::findOrFail($request->organization_id);
        return self::successResponse(data: OrganizationResource::make($organization->load('city.region')));
    }


    public function getProducts(Request $request ,Product $product ,PageRequest $pageRequest)
    {
        $organization = Organization::findOrFail($request->organization_id);
        $products = $organization->visibleProducts()
            ->with([
                'assignedOrganizations' => function ($query) use ($organization) {
                    $query->where('organizations.id', $organization->id)
                        ->withPivot('discount');
                },
            ])
            ->filter($request, (array) $product->filterableColumns)
            ->orderBy('price', $request->price ?? 'asc')
            ->paginate($pageRequest->page_count);

        if ($organization->products->isEmpty()) {
            return self::successResponse( message:__('application.no_products_assigned'));
        }
        return self::successResponsePaginate(
            data: ProductResource::collection($products->through(function($product) use ($organization) {
                return (new ProductResource($product))->setOrganization($organization);
            }))->response()->getData(true)
        );
    }

    public function getProduct(Request $request ,Product $product)
    {
        $organization = Organization::findOrFail($request->organization_id);
        $org_products = $product->organizations->pluck('id')->toArray();
        if (!in_array($request->organization_id , $org_products))
        {
            return self::failResponse(422, __('application.product_not_found'));
        }
        $product->load([
            'assignedOrganizations' => function ($query) use ($organization) {
                $query->where('organizations.id', $organization->id)
                    ->withPivot('discount');
            }
        ]);

        return self::successResponse(
            data: (new ProductDetailsResource($product))->setOrganization($organization)
        );
    }

    public function getOffers(Request $request )
    {
        $offers = Offer::whereOrganizationId($request->organization_id)->get();
        if ($offers->isEmpty()) {
            return self::successResponse( message:__('application.no_offers_assigned'));
        }
        return self::successResponse(data: OfferResource::collection($offers));
    }

    public function getOffer(Request $request ,Offer $offer)
    {
        if ($offer->organization_id != $request->organization_id) {
            return self::failResponse(422, __('application.product_not_found'));
        }
        return self::successResponse(data: OfferDetailsResource::make($offer));
    }

    public function getCategories(Request $request)
    {
        $mainCategories = Category::whereIn('id',
            Product::whereIn('id',
                OrganizationProduct::where('organization_id', $request->organization_id)
                    ->pluck('product_id')
            )->pluck('sub_category_id')
        )->pluck('parent_id');

        $categories = Category::whereIn('id', $mainCategories)->get();

        return self::successResponse(data: CategoryResource::collection($categories));
    }

    public function getSubCategories(Category $category): JsonResponse
    {
        $categories = Category::with('products','parent')->where('parent_id',$category->id)->get();
        return self::successResponse(data:SubCategoryResource::collection($categories));
    }
}
