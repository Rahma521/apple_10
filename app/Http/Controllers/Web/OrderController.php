<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\OrderResource;
use App\Http\Services\OrderService;
use App\Models\Cart;
use App\Models\Order;
use App\Http\Services\PaymentService;
use App\Traits\ResponseTrait;
use DB;
use Exception;

class OrderController extends Controller
{
    use ResponseTrait;

    protected OrderService $orderService;
    protected PaymentService $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    /**
     * Get user orders
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::where('user_id', auth('sanctum')->user()->id)
            ->with(['orderItems.product', 'orderItems.offer'])
            ->latest()
            ->get();

        return self::successResponse(
            data: OrderResource::collection($orders)
        );
    }

    /**
     * Create a new order
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('sanctum')->user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart || $cart->cartItems->isEmpty()) {
                return self::errorResponse('Cart is empty', 400);
            }

            $orderData = $request->validated();

            // If address data is provided, create new address
            if (isset($orderData['address'])) {
                $addressResult = $this->createAddress($orderData['address']);

                // If address creation failed, return the error
                if (!$addressResult['success']) {
                    return self::errorResponse($addressResult['message'], 422);
                }
                $orderData['address_id'] = $addressResult['address']->id;
            }

            $order = $this->orderService->createOrder($orderData, $cart->id);

            // Empty the cart after successful order creation
           // $cart->cartItems()->delete();

            DB::commit();
            return self::successResponse(
                message: 'Order created successfully',
                data: OrderResource::make($order)
            );

        } catch (Exception $e) {
            DB::rollBack();
            return self::errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Create a new address
     *
     * @param array $addressData
     * @return array
     */
    private function createAddress(array $addressData)
    {
        try {
            $address = $this->orderService->createOrUpdateAddress($addressData);

            return [
                'success' => true,
                'address' => $address,
                'message' => 'Address created successfully'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get specific order
     *
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        if ($order->user_id !== auth('sanctum')->user()->id) {
            return self::errorResponse('Unauthorized', 403);
        }

        return self::successResponse(
            data: OrderResource::make($order->load(['orderItems.product', 'orderItems.offer']))
        );
    }

    public function processPayment(PaymentRequest $request, Order $order)
    {
        if ($order->payment_status === 'paid') {
            return self::errorResponse('Order is already paid', 400);
        }

        if ($order->user_id !== auth('sanctum')->user()->id) {
            return self::errorResponse('Unauthorized', 403);
        }

        try {
            $cardData = $request->validated();
            $result = $this->orderService->processOrderPayment($order, $cardData);

            if (!$result['success']) {
                return self::errorResponse(
                    $result['message'] ?? 'Payment failed',
                    422
                );
            }

            return self::successResponse(
                message: 'Payment processed successfully',
                data: [
                    'order' => new OrderResource($order->fresh()),
                    'payment' => $result['data']
                ]
            );

        } catch (Exception $e) {
            return self::errorResponse($e->getMessage(), 422);
        }
    }
}
