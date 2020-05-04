<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Functional;

use PHPUnit\Framework\TestCase;
use SashaMart\CartDiscount\Discount\DiscountEnforcer;
use SashaMart\CartDiscount\Entity\Cart;
use SashaMart\CartDiscount\Entity\Good;
use SashaMart\CartDiscount\Entity\User;

class DiscountEnforcerTest extends TestCase
{
    private $discountEnforcer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->discountEnforcer = new DiscountEnforcer();
    }

    public function testPensionDiscount()
    {
        $user = new User(2, true);
        $cart = new Cart($user);
        $cart->addGood(new Good('good1', 700))
            ->addGood(new Good('good2', 1300));

        $this->discountEnforcer->enforceDiscountToCart($cart);

        $this->assertEquals(100, $cart->getDiscountAmount());
        $this->assertEquals(665, $cart->getItems()['good1']->getTotalPriceWithDiscount());
        $this->assertEquals(1235, $cart->getItems()['good2']->getTotalPriceWithDiscount());
    }

    public function testBigCartDiscount()
    {
        $user = new User(2, false);
        $cart = new Cart($user);
        $cart->addGood(new Good('good1', 2000))
            ->addGood(new Good('good2', 1500))
            ->addGood(new Good('good3', 6000))
            ->changeGoodsQuantity('good2', 2);

        $this->discountEnforcer->enforceDiscountToCart($cart);

        $this->assertEquals(500, $cart->getDiscountAmount());
        $this->assertEquals(1909, round($cart->getItems()['good1']->getTotalPriceWithDiscount()));
        $this->assertEquals(2864, round($cart->getItems()['good2']->getTotalPriceWithDiscount()));
    }

    public function testFirstPurchaseDiscount()
    {
        $user = new User(0, false);
        $cart = new Cart($user);
        $cart->addGood(new Good('good1', 3000))
            ->addGood(new Good('good2', 2000));

        $this->discountEnforcer->enforceDiscountToCart($cart);

        $this->assertEquals(100, $cart->getDiscountAmount());
        $this->assertEquals(2940, $cart->getItems()['good1']->getTotalPriceWithDiscount());
        $this->assertEquals(1960, $cart->getItems()['good2']->getTotalPriceWithDiscount());
    }
}