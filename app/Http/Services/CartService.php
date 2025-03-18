<?php

namespace App\Http\Services;

use App\Models\Cart;
use App\Models\CartItems;

class CartService
{
    /**
     * Add or update item in cart
     *
     * @param int $cartId
     * @param array $cartData
     * @return CartItems
     */
    public function addOrUpdateCartItem($cartId, array $cartData)
    {
        $cartItem = CartItems::where('cart_id', $cartId)
            ->where('item_type', $cartData['item_type'])
            ->where(function ($query) use ($cartData) {
                if (isset($cartData['product_id'])) {
                    $query->where('product_id', $cartData['product_id']);
                }
                if (isset($cartData['offer_id'])) {
                    $query->where('offer_id', $cartData['offer_id']);
                }
            })->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem['quantity'] + 1,
            ]);
        } else {
            $cartItem = CartItems::create([
                'cart_id' => $cartId,
                'item_type' => $cartData['item_type'],
                'product_id' => $cartData['product_id'] ?? null,
                'offer_id' => $cartData['offer_id'] ?? null,
                'quantity' => 1,
            ]);
        }

        return $cartItem;
    }
    /**
     * Calculate cart prices including discounts
     *
     * @param int $cartId
     * @return array
     */
    public function calculatePrices($cartId)
    {
        $cartItems = CartItems::where('cart_id', $cartId)->get();
        $totalBeforeDiscount = 0;
        $totalAfterDiscount = 0;
        $organizationId = auth('sanctum')->user()->organization_id;

        foreach ($cartItems as $item) {
            if ($item->item_type == 'product') {
                $this->calculateProductPrices(
                    $item,
                    $organizationId,
                    $totalBeforeDiscount,
                    $totalAfterDiscount
                );
            } elseif ($item->item_type == 'offer') {
                $this->calculateOfferPrices(
                    $item,
                    $totalBeforeDiscount,
                    $totalAfterDiscount
                );
            }
        }

        $this->updateCartTotals($cartId, $totalBeforeDiscount, $totalAfterDiscount);

        return [
            'total_before_discount' => $totalBeforeDiscount,
            'total_after_discount' => $totalAfterDiscount
        ];
    }

    /**
     * Calculate prices for product items
     *
     * @param CartItems $item
     * @param int $organizationId
     * @param float &$totalBeforeDiscount
     * @param float &$totalAfterDiscount
     */
    private function calculateProductPrices(
        CartItems $item,
                  $organizationId,
                  &$totalBeforeDiscount,
                  &$totalAfterDiscount
    ) {
        $product = $item->product;
        $itemTotalBeforeDiscount = $product->price * $item->quantity;
        $discount = $product->getDiscountForOrganization($organizationId);

        if ($discount !== null) {
            $discountAmount = ($discount / 100) * $itemTotalBeforeDiscount;
            $itemTotalAfterDiscount = $itemTotalBeforeDiscount - $discountAmount;
        } else {
            $itemTotalAfterDiscount = $itemTotalBeforeDiscount;
        }

        $totalBeforeDiscount += $itemTotalBeforeDiscount;
        $totalAfterDiscount += $itemTotalAfterDiscount;
    }

    /**
     * Calculate prices for offer items
     *
     * @param CartItems $item
     * @param float &$totalBeforeDiscount
     * @param float &$totalAfterDiscount
     */
    private function calculateOfferPrices(
        CartItems $item,
                  &$totalBeforeDiscount,
                  &$totalAfterDiscount
    ) {
        $offer = $item->offer;
        $totalBeforeDiscount += $offer->getPriceBeforeDiscount() * $item->quantity;
        $totalAfterDiscount += $offer->getPriceAfterDiscount() * $item->quantity;
    }

    /**
     * Update cart totals in the database
     *
     * @param int $cartId
     * @param float $totalBeforeDiscount
     * @param float $totalAfterDiscount
     */
    private function updateCartTotals($cartId, $totalBeforeDiscount, $totalAfterDiscount)
    {
        Cart::find($cartId)->update([
            'total_before_discount' => $totalBeforeDiscount,
            'total_after_discount' => $totalAfterDiscount
        ]);
    }
}
