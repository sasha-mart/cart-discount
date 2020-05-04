<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Discount;

use SashaMart\CartDiscount\Entity\Cart;

class PercentDiscount extends AbstractDiscount
{
    public function getAmountForCart(Cart $cart): float
    {
        $cartBaseAmount = $cart->getTotalBaseAmount();

        return $cartBaseAmount * $this->value / 100;
    }
}