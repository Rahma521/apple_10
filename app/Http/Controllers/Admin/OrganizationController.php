<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OfferTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignProductRequest;
use App\Http\Requests\PageRequest;
use App\Http\Requests\Store\StoreOrganizationRequest;
use App\Http\Requests\Update\UpdateOrganizationRequest;
use App\Http\Requests\UpdateDiscountForAssignProductRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\ProductResource;
use App\Models\Organization;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    use ResponseTrait;

    public function index(Request $request, Organization $organization, PageRequest $pageRequest): JsonResponse
    {
        $organizations = Organization::with('educationLevel')->filter($request, (array)$organization->filterableColumns)->paginate($pageRequest->page_count);

        return self::successResponsePaginate(data: OrganizationResource::collection($organizations)->response()->getData(true));
    }

    public function store(StoreOrganizationRequest $request)
    {
        $organization = Organization::create($request->validated());

        if ($request->files) {
            $files = $request->allFiles()['files'];
            handleMultiMediaUploads($files, $organization);
        }

        return self::successResponse(message: __('application.added'), data: OrganizationResource::make($organization->load('city.region')));
    }

    public function show(Organization $organization)
    {
        return self::successResponse(data: OrganizationResource::make($organization->load('city.region')));
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        if ($request->has('files')) {
            $files = $request->allFiles()['files'] ?? null;

            if ($files) {
                handleMultiMediaUploads($files, $organization, true);
            }
        }
        return self::successResponse(data: OrganizationResource::make($organization->load('city.region')));

    }

    public function destroy(Organization $organization)
    {
        clearMediaByModelType('app\Models\Organization', $organization->id);
        $organization->delete();

        return self::successResponse(message: __('application.deleted'));
    }

    public function getAssignedOrganization(Request $request, Organization $organization, Product $product, PageRequest $pageRequest): JsonResponse
    {
        $products = $organization->products()
            ->with([
                'assignedOrganizations' => function ($query) use ($organization) {
                    $query->where('organizations.id', $organization->id)
                        ->withPivot('discount');
                },
                'offers' => function($query) use ($organization) {
                    $query->where('organization_id', $organization->id)
                        ->where('type', OfferTypeEnum::single);
                }
            ])
            ->filter($request, (array) $product->filterableColumns)
            ->paginate($pageRequest->page_count);

        if ($products->isEmpty()) {
            return self::successResponse(message: __('application.no_products_assigned'));
        }

        return self::successResponsePaginate(
            data: ProductResource::collection($products->through(function($product) use ($organization) {
                return (new ProductResource($product))->setOrganization($organization);
            }))->response()->getData(true)
        );
    }

    public function getAssignedOrganizationWithDiscount(Request $request, Organization $organization, Product $product, PageRequest $pageRequest): JsonResponse
    {
        // First get products with discount > 0
        $products = $organization->products()
            ->with([
                'assignedOrganizations' => function ($query) use ($organization) {
                    $query->where('organizations.id', $organization->id)
                        ->withPivot('discount');
                }
            ])
            ->whereHas('assignedOrganizations', function ($query) use ($organization) {
                $query->where('organizations.id', $organization->id)
                    ->where('discount', '>', 0);
            })
            ->filter($request, (array) $product->filterableColumns);

        // Add a subquery to order by discount without additional joins
        $discountSubquery = \DB::table('organization_product')
            ->select('discount')
            ->whereColumn('product_id', 'products.id')
            ->where('organization_id', $organization->id)
            ->limit(1);

        $products = $products->orderByDesc($discountSubquery)
            ->paginate($pageRequest->page_count);

        if ($products->isEmpty()) {
            return self::successResponse(message: __('application.no_products_with_discount'));
        }

        return self::successResponsePaginate(
            data: ProductResource::collection($products->through(function($product) use ($organization) {
                return (new ProductResource($product))->setOrganization($organization);
            }))->response()->getData(true)
        );
    }

    public function updateDiscountForAssignedProduct(UpdateDiscountForAssignProductRequest $request, Organization $organization)
    {
        $productIds = $request->products;
        $discount = $request->discount;

        if (!empty($productIds)) {
            $organization->assignedProducts()
                ->wherePivotIn('product_id', $productIds)
                ->update(['discount' => $discount]);
        }

        return self::successResponse(message: __('application.updated'));
    }

}
