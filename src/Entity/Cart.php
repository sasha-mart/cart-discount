<?php
declare(strict_types=1);
namespace SashaMart\CartDiscount\Entity;

use SashaMart\CartDiscount\Exception\NotFoundInCartException;

class Cart
{
    /**
     * @var CartItem[]
     */
    private $items = [];

    /**
     * @var float
     */
    private $discountAmount = 0;

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Good $good
     * @return $this
     */
    public function addGood(Good $good): self
    {
        if (!isset($this->goods[$good->getUniqueName()])) {
            $this->items[$good->getUniqueName()] = new CartItem($this, $good, 1);
        } else {
            $this->items[$good->getUniqueName()]->increaseQuantity();
        }

        return $this;
    }

    /**
     * @param string $goodName
     * @param int $quantity
     * @throws NotFoundInCartException
     */
    public function changeGoodsQuantity(string $goodName, int $quantity): void
    {
        if (!isset($this->items[$goodName])) {
            throw new NotFoundInCartException();
        }

        $this->items[$goodName]->setQuantity($quantity);
    }

    /**
     * @param string $goodName
     * @throws NotFoundInCartException
     */
    public function deleteCartItem(string $goodName): void
    {
        if (!isset($this->items[$goodName])) {
            throw new NotFoundInCartException();
        }

        unset($this->items[$goodName]);
    }

    /**
     * @return float
     */
    public function getDiscountPercent(): float
    {
        return $this->discountAmount * 100 / $this->getTotalBaseAmount();
    }

    /**
     * @return float
     */
    public function getTotalBaseAmount(): float
    {
        $result = 0;
        foreach ($this->items as $cartItem) {
            $result += $cartItem->getTotalBasePrice();
        }

        return $result;
    }

    /**
     * @return float
     */
    public function getTotalPriceWithDiscount(): float
    {
        return $this->getTotalBaseAmount() - $this->discountAmount;
    }

    /**
     * @param float $discountAmount
     */
    public function setDiscountAmount(float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return float
     */
    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}