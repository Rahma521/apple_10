<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTrait;

    public function index(Request $request, Order $order, PageRequest $pageRequest)
    {
        $orders = Order::with(['orderItems.product', 'orderItems.offer'])->filter($request, (array)$order->filterableColumns)
            ->orderBy('created_at', 'desc')->paginate($pageRequest->page_count);
        return self::successResponsePaginate(data: OrderResource::collection($orders)->response()->getData(true));
    }

    public function show(Order $order)
    {
        return self::successResponse(
            data: OrderResource::make($order->load(['orderItems.product', 'orderItems.offer']))
        );
    }

}
