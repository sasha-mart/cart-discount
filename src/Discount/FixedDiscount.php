<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Discount;

use SashaMart\CartDiscount\Entity\Cart;

class FixedDiscount extends AbstractDiscount
{

    public function getAmountForCart(Cart $cart): float
    {
        return $this->value;
    }
}