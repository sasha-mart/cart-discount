<?php
declare(strict_types=1);
namespace SashaMart\CartDiscount\Entity;

class User
{
    /**
     * @var int
     */
    private $purchaseQuantity;

    /**
     * @var bool
     */
    private $isPensioner;

    public function __construct(int $purchaseQuantity, bool $isPensioner)
    {
        $this->purchaseQuantity = $purchaseQuantity;
        $this->isPensioner = $isPensioner;
    }

    /**
     * @return int
     */
    public function getPurchaseQuantity(): int
    {
        return $this->purchaseQuantity;
    }

    /**
     * @return bool
     */
    public function isPensioner(): bool
    {
        return $this->isPensioner;
    }
}