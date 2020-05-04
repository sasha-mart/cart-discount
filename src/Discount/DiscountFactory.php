<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Discount;

use SashaMart\CartDiscount\Entity\Cart;

class DiscountFactory
{
    private const FIRST_PURCHASE_DISCOUNT_AMOUNT = 100;
    private const PENSIONER_DISCOUNT_PERCENT = 5;
    private const BIG_CART_DISCOUNT_AMOUNT = 500;

    private const SUM_FOR_BIG_CART_DISCOUNT = 10000;

    /**
     * @param Cart $cart
     * @return DiscountInterface|null
     */
    public static function createDiscountForCart(Cart $cart): ?DiscountInterface
    {
        $possibleDiscounts = [];

        if ($cart->getUser()->getPurchaseQuantity() === 0) {
            $possibleDiscounts[] = new FixedDiscount(self::FIRST_PURCHASE_DISCOUNT_AMOUNT);
        }
        if ($cart->getUser()->isPensioner()) {
            $possibleDiscounts[] = new PercentDiscount(self::PENSIONER_DISCOUNT_PERCENT);
        }
        if ($cart->getTotalBaseAmount() >= self::SUM_FOR_BIG_CART_DISCOUNT) {
            $possibleDiscounts[] = new FixedDiscount(self::BIG_CART_DISCOUNT_AMOUNT);
        }

        switch (true) {
            case count($possibleDiscounts) === 0:
                return null;
            case count($possibleDiscounts) === 1:
                return $possibleDiscounts[0];
            default:
                return self::chooseMaxDiscount($possibleDiscounts, $cart);
        }

    }

    /**
     * @param DiscountInterface[] $possibleDiscounts
     * @param Cart $cart
     * @return DiscountInterface
     */
    private static function chooseMaxDiscount(array $possibleDiscounts, Cart $cart): DiscountInterface
    {
        $result = 0;
        foreach ($possibleDiscounts as $possibleDiscount) {
            if ($possibleDiscount->getAmountForCart($cart) > $result) {
                $result = $possibleDiscount;
            }
        }

        return $result;
    }
}