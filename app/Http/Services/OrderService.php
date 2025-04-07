<?php

namespace App\Http\Services;

use App\Enums\OrderStatusEnum;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\User;
use Illuminate\Support\Str;

class OrderService
{

    protected CartService $cartService;
    protected PaymentService $paymentService;


    public function __construct(CartService $cartService , PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->paymentService = $paymentService;

    }
    /**
     * Create or update user address
     *
     * @param array $addressData
     * @return Address
     */
    public function createOrUpdateAddress(array $addressData)
    {
        $userId = auth('sanctum')->user()->id;

        // Create new address
        return Address::create([
            'user_id' => $userId,
            'first_name' => $addressData['first_name'],
            'last_name' => $addressData['last_name'],
            'phone' => $addressData['phone'],
            'zip_code' => $addressData['zip_code']?? NULL,
            'city_id' => $addressData['city_id']?? NULL,
            'district' => $addressData['district']?? NULL,
            'short_address' => $addressData['short_address'],
            'full_address' => $addressData['full_address']?? NULL,
            'type' => $addressData['type']
        ]);
    }
    /**
     * Create a new order from cart
     *
     * @param array $orderData
     * @param int $cartId
     * @return Order
     */
    public function createOrder(array $orderData, $cartId)
    {
        $total = $this->getCartTotal($cartId);

        if ($total == 0) {
            throw new \Exception('Cannot place an order with a total of 0.');
        }
        // Create or get address
        $addressId = isset($orderData['address_id'])
            ? $this->createOrUpdateAddress($orderData['address'])->id
            : $orderData['address_id'];

        // Create the order
        $order = Order::create([
            'user_id' => auth('sanctum')->user()->id,
            'cart_id' => $cartId,
            'address_id' => $addressId,
            'order_number' => $this->generateOrderNumber(),
            'payment_method' => $orderData['payment_method'],
            'order_status' => OrderStatusEnum::pending->value,
            'delivery_cost' => $this->getDeliveryCost(auth('sanctum')->user()->id),
            'total' => $this->getCartTotal($cartId) + $this->getDeliveryCost(auth('sanctum')->user()->id),
        ]);

        // Transfer cart items to order items
        $this->transferCartItemsToOrder($cartId, $order->id);

        return $order;
    }

    public function processOrderPayment(Order $order, array $paymentData)
    {
        try {
            $paymentResult = $this->paymentService->processPayment($order, $paymentData);

            if ($paymentResult['success']) {
                // Delete cart items
                $cart = Cart::where('id', $order->cart_id)->first();
                $cart->cartItems()->delete();
                // Update order status if needed
                $order->update(['order_status' => OrderStatusEnum::placed->value]);
            }

            return $paymentResult;
        } catch (Exception $e) {
            throw new Exception('Payment processing failed: ' . $e->getMessage());
        }
    }
    /**
     * Transfer cart items to order items
     *
     * @param int $cartId
     * @param int $orderId
     * @return void
     */
    private function transferCartItemsToOrder($cartId, $orderId)
    {
        $cartItems = CartItems::where('cart_id', $cartId)->get();

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $orderId,
                'item_type' => $cartItem->item_type,
                'product_id' => $cartItem->product_id,
                'offer_id' => $cartItem->offer_id,
                'quantity' => $cartItem->quantity,
                'price' => $this->getItemPrice($cartItem),
                'total' => $this->getItemPrice($cartItem) * $cartItem->quantity
            ]);
        }
    }

    /**
     * Get item price based on type (product or offer)
     *
     * @param CartItems $cartItem
     * @return float
     */
    private function getItemPrice(CartItems $cartItem)
    {
        if ($cartItem->item_type === 'product') {
            return $cartItem->product->price;
        } else {
            return $cartItem->offer->getPriceAfterDiscount();
        }
    }

    /**
     * Generate unique order number
     *
     * @return string
     */
    private function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(Str::random(10));
    }

    /**
     * Get cart total
     *
     * @param int $cartId
     * @return float
     */
    private function getCartTotal($cartId)
    {
        $prices = $this->cartService->calculatePrices($cartId);
       // round($this->total_after_discount + ($this->total_after_discount * 0.15), 2)
        return $prices['total_after_discount'] + ($prices['total_after_discount'] * 0.15)  ;
    }

    private function getDeliveryCost($userId)
    {

        $user = User::find($userId);
        return $user->organization?->delivery_price;
    }
}
