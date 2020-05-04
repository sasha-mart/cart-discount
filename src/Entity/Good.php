<?php
declare(strict_types=1);

namespace SashaMart\CartDiscount\Entity;

class Good
{
    /**
     * @var string
     */
    private $uniqueName;

    /**
     * @var float
     */
    private $basePrice;

    public function __construct(string $uniqueName, float $basePrice)
    {
        $this->uniqueName = $uniqueName;
        $this->basePrice = $basePrice;
    }

    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }

    /**
     * @return float
     */
    public function getBasePrice(): float
    {
        return $this->basePrice;
    }
}