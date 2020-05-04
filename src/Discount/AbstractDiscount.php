<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Discount;

abstract class AbstractDiscount implements DiscountInterface
{
    /**
     * @var float
     */
    protected $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }
}