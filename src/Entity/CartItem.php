<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Entity;

class CartItem
{
    /**
     * @var Good
     */
    private $good;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(Cart $cart, Good $good, int $quantity)
    {
        $this->cart = $cart;
        $this->good = $good;
        $this->quantity = $quantity;
    }

    public function increaseQuantity(): void
    {
        ++$this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getBasePrice(): float
    {
        return $this->good->getBasePrice();
    }

    /**
     * @return float|int
     */
    public function getTotalBasePrice(): float
    {
        return $this->getBasePrice() * $this->quantity;
    }

    /**
     * Returns total price of this kind of goods in the cart with discount
     * @return float
     */
    public function getTotalPriceWithDiscount(): float
    {
        return $this->getBasePrice() * $this->quantity * (100 - $this->cart->getDiscountPercent()) / 100;
    }

    /**
     * Returns price of one good in the cart with discount
     * @return float
     */
    public function getItemPriceWithDiscount(): float
    {
        return $this->getBasePrice() * (100 - $this->cart->getDiscountPercent()) / 100;
    }
}