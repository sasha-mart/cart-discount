<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Discount;

use SashaMart\CartDiscount\Entity\Cart;

interface DiscountInterface
{
    public function getAmountForCart(Cart $cart): float ;
}