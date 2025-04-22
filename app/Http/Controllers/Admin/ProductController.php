<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignProductRequest;
use App\Http\Requests\PageRequest;
use App\Http\Requests\Store\StoreProductRequest;
use App\Http\Requests\Update\UpdateProductRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductResource;
use App\Models\Color;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProductController extends Controller
{
    use ResponseTrait;

    public function index(Request $request, Product $product, PageRequest $pageRequest): JsonResponse
    {
        $products = Product::with('assignedOrganizations')->filter($request, (array) $product->filterableColumns)->paginate($pageRequest->page_count);
        return self::successResponsePaginate(data: ProductResource::collection($products)->response()->getData(true));
    }

    public function getOMStockQty(Request $request): JsonResponse
    {
        try {
            // For testing purposes, using static data as per documentation
            $requestData = [
                [
                    "CompId" => $request->CompId,
                    "ItemId" => $request->ItemId,
                    "LocId" => $request->LocId,
                    //"compId" => "ACR",
                    // "compId" => "ACL",

                   // "ItemId" => "MC7U4AB/A",
                    // "ItemId" => "MYN13AH/A",

                    //"LocId" => "RAJHI"
                    //"LocId" => "BLU"
                ]
            ];

            $userId = "Basic QTQ3NjE=";
            // Make the API request with Basic Auth
            $response = Http::withBasicAuth($userId, '')->timeout(30)->post('https://erptrvksa.midisglobal.com/api/GetOMStockQty', $requestData);
            if ($response->successful()) {
                // The API might return HTTP 200 but still contain error information in the response body
                $responseData = $response->json();

                return response()->json([
                    'status' => 200,
                    'message' => 'Stock quantity data retrieved',
                    'data' => $responseData
                ], 200);
            }

            // Handle specific error codes based on documentation
            $statusCode = $response->status();
            $errorMessage = 'Failed to retrieve stock quantity';

            switch ($statusCode) {
                case 400:
                    $errorMessage = 'Bad request: Invalid format or company ID';
                    break;
                case 401:
                    $errorMessage = 'Unauthorized: Authentication failed';
                    break;
                case 406:
                    $errorMessage = 'Not Acceptable: Location or item does not exist';
                    break;
                case 500:
                    $errorMessage = 'Internal Server Error';
                    break;
            }

            return $this->errorResponse($errorMessage, $statusCode);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while retrieving stock quantity: ' . $e->getMessage(), 500);
        }
    }

    public function checkQuantityAvailable(Request $request): JsonResponse
    {
        try {
            // For testing purposes, using static data as per documentation
            $requestData = [[
                "CompId" => $request->CompId,
                "ITEM_ID" => $request->ITEM_ID,
                "WHSE_ID" => $request->WHSE_ID,
                "QTY" => $request->QTY,
                // "CASE_REF" => "CASE123"
            ]];

            // Add authentication header as specified in the documentation
            // Note: In production, store userId in .env file
            $userId = "QTQ3NjE="; // Replace with the actual User ID provided by Midis

            // Make the API request with Basic Auth
            $response = Http::withBasicAuth($userId, '')
                ->post('https://erptrvksa.midisglobal.com/api/ChkQtyAvailable', $requestData);

            // Check if the request was successful (HTTP 200)
            if ($response->successful()) {
                $responseData = $response->json();

                return response()->json([
                    'status' => 200,
                    'message' => 'Quantity availability checked successfully',
                    'data' => $responseData
                ], 200);
            }

            // Handle specific error codes based on documentation
            $statusCode = $response->status();
            $errorMessage = 'Failed to check quantity availability';

            switch ($statusCode) {
                case 400:
                    $errorMessage = 'Bad request: Invalid format or company ID';
                    break;
                case 401:
                    $errorMessage = 'Unauthorized: Authentication failed';
                    break;
                case 406:
                    // Check specific error codes for this API
                    $responseData = $response->json();
                    if (isset($responseData[0]['errorCode'])) {
                        switch ($responseData[0]['errorCode']) {
                            case 'TRV_CQA_1040':
                                $errorMessage = 'Item ID does not exist';
                                break;
                            case 'TRV_CQA_1041':
                                $errorMessage = 'Warehouse ID is not mapped';
                                break;
                            case 'TRV_CQA_1042':
                                $errorMessage = 'Item ID does not exist in specified location';
                                break;
                            case 'TRV_CQA_1043':
                                $errorMessage = 'Quantity should be greater than 0';
                                break;
                        }
                    }
                    break;
                case 500:
                    $errorMessage = 'Internal Server Error';
                    break;
            }

            return $this->errorResponse($errorMessage, $statusCode);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while checking quantity availability: ' . $e->getMessage(), 500);
        }
    }
    public function store(StoreProductRequest $request)
    {
        $validatedData =$request->validated();

        $product = Product::create($validatedData);

        if(isset($validatedData['files']) && $validatedData['files'])
        {
            handleMultiMediaUploads($validatedData['files'], $product);
        }

        if(isset($validatedData['color']) && $validatedData['color'])
        {
            handleMultiMedia($validatedData['color'], $product);
        }

        return self::successResponse(message: __('application.added'), data: ProductResource::make($product));
    }

    public function show(Product $product)
    {
        $product->load(['assignedOrganizations' => function ($query) {
            $query->withPivot('discount');
        }]);

        return self::successResponse(data: ProductDetailsResource::make($product));
    }


    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $product->update($validatedData);

        if ($request->hasFile('files.product_banner')) {
            $files = $request->allFiles();
            if (isset($files['files']['product_banner'])) {
                handleMultiMedia([$files['files']['product_banner']], $product, true, 'product_banner');
            }
        }

        if ($request->hasFile('color.color_img')) {
            $files = $request->allFiles();
            if (isset($files['color']['color_img'])) {
                handleMultiMedia([$files['color']['color_img']], $product, true, 'color_img');
            }
        }

        return self::successResponse(message: __('application.updated'), data: ProductResource::make($product));
    }

    public function destroy(Product $product)
    {
        clearMediaByModelType('app\Models\Product', $product->id);
        $product->delete();

        return self::successResponse(message: __('application.deleted'));
    }

    public function getAssignedProducts(Product $product): JsonResponse
    {
        $product->load('organizations');
        if ($product->organizations->isEmpty()) {
            return self::successResponse( message:__('application.no_products_assigned'));
        }
        return self::successResponse(message: __('application.success'), data: organizationResource::collection($product->organizations));
    }

    public function assignProducts(Product $product, AssignProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
       // $product->organizations()->detach();
        $syncData = collect($validated['organizations'])->mapWithKeys(function ($organization) {
            return [
                $organization['id'] => [
                    'discount' => $organization['discount']?? null,
                ]
            ];
        })->toArray();
        $product->organizations()->sync($syncData);
        return self::successResponse(message: __('application.products_retrieved_successfully'));

    }

    public function unassignProducts(Product $product, AssignProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $organizationIds = collect($validated['organizations'])->pluck('id')->toArray();

        $product->organizations()->detach($organizationIds);
        return self::successResponse(message: __('application.products_unassigned_successfully'));
    }


}
