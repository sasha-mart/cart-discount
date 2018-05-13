<?php

namespace SashaMart\CartDiscount;

use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testDiscountedTotal()
    {
        $user = new User(1, 30, 'm');
        $cart = new Cart($user);

        $items = [
            [
                'id' => 1,
                'price' => 200
            ],
            [
                'id' => 2,
                'price' => 300
            ],
            [
                'id' => 3,
                'price' => 9800
            ]];

        foreach ($items as $item) {
            $cart->addItem($item);
        }

        $this->assertEquals(9700, $cart->getDiscountedTotalAmount());
        $cart->addOrder();

        $user = new User(1, 60, 'w');
        $cart = new Cart($user);

        $items = [
            [
                'id' => 1,
                'price' => 200
            ],
            [
                'id' => 2,
                'price' => 300
            ],
            [
                'id' => 3,
                'price' => 500
            ]];
        foreach ($items as $item) {
            $cart->addItem($item);
        }

        $this->assertEquals(950, $cart->getDiscountedTotalAmount());
        $cart->addOrder();
    }

}