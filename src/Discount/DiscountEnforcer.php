<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Discount;

use SashaMart\CartDiscount\Entity\Cart;

class DiscountEnforcer
{
    /**
     * @param Cart $cart
     */
    public function enforceDiscountToCart(Cart $cart): void
    {
        $discount = DiscountFactory::createDiscountForCart($cart);
        $cart->setDiscountAmount($discount->getAmountForCart($cart));
    }
}