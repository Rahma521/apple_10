<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Services\CartService;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Organization;
use App\Traits\ResponseTrait;
use DB;

class CartController extends Controller
{
    use ResponseTrait;

    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $user = auth('sanctum')->user();
        $user_id = $user->id;
        $organization = $user?->organization;

        // Load cart with cart items and products
        $cart = Cart::with(['cartItems.product', 'cartItems.offer'])
            ->where('user_id', $user_id)
            ->first();

        if(!$cart){
            return response()->json([
                'status' => 404,
                'message' => 'Resource not found.',
            ], 404);
        }

        // If we have an organization, load the specific assigned organization relationship for each product
        if ($organization) {
            foreach ($cart->cartItems as $cartItem) {
                if ($cartItem->item_type === 'product' && $cartItem->product) {
                    // Load the specific organization relationship for this product
                    $cartItem->product->load([
                        'assignedOrganizations' => function ($query) use ($organization) {
                            $query->where('organizations.id', $organization->id)
                                ->withPivot('discount');
                        }
                    ]);
                }
            }
        }

        $prices = $this->cartService->calculatePrices($cart->id);
        $cart->total_before_discount = $prices['total_before_discount'];
        $cart->total_after_discount = $prices['total_after_discount'];

        // Pass the organization to the cart resource
        $cartResource = CartResource::make($cart);

        return self::successResponse(data: $cartResource);
    }
    public function store(CartRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('sanctum')->user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                $cart = Cart::create(['user_id' => $user->id]);
            }

            $organizationMaxOrder = Organization::whereId($user->organization_id)->first()->max_order;
            $currentCartQuantity = CartItems::where('cart_id', $cart->id)->sum('quantity');

            if ($currentCartQuantity >= $organizationMaxOrder) {
                return self::errorResponse(__('application.max_order'), 400);
            }

            $cartData = $request->safe()->all();
            $this->cartService->addOrUpdateCartItem($cart->id, $cartData);

            $this->cartService->calculatePrices($cart->id);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return self::failResponse(422, $e->getMessage());
        }

        return self::successResponse(message: __('application.added'));
    }

    public function deleteItem(CartItems $cartItem)
    {
        $cartId = $cartItem->cart_id;
        if ($cartItem->quantity == 1) {
            $cartItem->delete();
        } else {
            $cartItem->update([
                'quantity' =>  $cartItem['quantity']-1,
            ]);
        }
        $this->cartService->calculatePrices($cartId);
        return self::successResponse(message: __('application.deleted'));
    }

    public function destroyItem(CartItems $cartItem)
    {
        $cartId = $cartItem->cart_id;
        $cartItem->delete();
        $this->cartService->calculatePrices($cartId);
        return self::successResponse(message: __('application.deleted'));
    }

    public function emptyCart(Cart $cart)
    {
        $cart->cartItems()->delete();
        return response()->json();
    }


}
