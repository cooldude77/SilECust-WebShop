<?php

namespace Silecust\WebShop\Service\Transaction\Order;

class PriceObject
{
    private float $discount;
    private float $taxRate;
    private float $shipping;

    // If external systems are used , these values will be
    // filled by own API
    // else they will be serialized and then unserialized values of records in Silecust
    private  array $basePriceArray =[];
    private  array $discountArray =[];
    private  array $taxRateArray =[];


    public function __construct(private float $basePrice)
    {
    }

    public function getBasePriceAmount(): float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): void
    {
        $this->basePrice = $basePrice;
    }

    public function getDiscountAmount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    public function getTaxRatePercentage(): float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getShipping(): float
    {
        return $this->shipping;
    }

    public function setShipping(float $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getBasePriceArray(): array
    {
        return $this->basePriceArray;
    }

    public function setBasePriceArray(array $basePriceArray): void
    {
        $this->basePriceArray = $basePriceArray;
    }

    public function getDiscountArray(): array
    {
        return $this->discountArray;
    }

    public function setDiscountArray(array $discountArray): void
    {
        $this->discountArray = $discountArray;
    }

    public function getTaxRateArray(): array
    {
        return $this->taxRateArray;
    }

    public function setTaxRateArray(array $taxRateArray): void
    {
        $this->taxRateArray = $taxRateArray;
    }


}