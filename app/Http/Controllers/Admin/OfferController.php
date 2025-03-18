<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Requests\Store\StoreOfferRequest;
use App\Http\Requests\Update\UpdateOfferRequest;
use App\Http\Resources\OfferDetailsResource;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Models\OfferProducts;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use ResponseTrait;

    public function index(Request $request, Offer $offer, PageRequest $pageRequest): JsonResponse
    {
        $offers = Offer::filter($request, (array)$offer->filterableColumns)->paginate($pageRequest->page_count);

        return self::successResponsePaginate(data: OfferResource::collection($offers)->response()->getData(true));
    }

    public function store(StoreOfferRequest $request)
    {
        DB::beginTransaction();
        try {

        $offerData = $request->safe()->except('products');
            $offerProducts =$request->safe()->all()['products'];
            $offer = Offer::create($offerData);

            foreach ($offerProducts as $product) {
               OfferProducts::create([
                   'offer_id' => $offer->id,
                   'product_id' => $product['id'],
                   'specific_discount' => $product['discount']??null,
               ]);
            }

            $offer->update(['price_before_discount' => $offer->getPriceBeforeDiscount(),
                'price_after_discount' => $offer->getPriceAfterDiscount()]);

            if(isset($request['files']) && $request['files'])
            {
                handleMultiMedia($request['files'], $offer);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return self::failResponse(422, $e->getMessage());
        }
        return self::successResponse(message: __('application.added'));
    }

    public function show(Offer $offer)
    {
        return self::successResponse(data: OfferDetailsResource::make($offer));
    }

    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        DB::beginTransaction();
        try {
            $offerData = $request->safe()->except('products');
            $offer->update($offerData);

            if ($request->has('products')) {
                $syncData = collect($request->validated()['products'])->mapWithKeys(function ($product) {
                    return [
                        $product['id'] => [ // This is the pivot key
                            'specific_discount' => $product['discount'] ?? null, // Additional pivot data
                        ]
                    ];
                })->toArray();

                $offer->products()->sync($syncData);
                $offer->update([
                    'price_before_discount' => $offer->getPriceBeforeDiscount(),
                    'price_after_discount' => $offer->getPriceAfterDiscount()
                ]);
            }

            if ($request->has('files') && $request->files) {
                handleMultiMedia($request['files'], $offer, true);
            }

            DB::commit();
            return self::successResponse(data: OfferResource::make($offer));

        } catch (Exception $e) {
            DB::rollBack();
            return self::failResponse(422, $e->getMessage());
        }
    }

    public function destroy(Offer $offer)
    {
        clearMediaByModelType('app\Models\Offer', $offer->id);
        $offer->delete();
        return self::successResponse(message: __('application.deleted'));
    }
}
