<?php

namespace App\Service\MasterData\Pricing\Item;

class PriceCalculator
{
    public function calculatePrice(PriceObject $priceObject): float
    {

        $basePrice = $priceObject->getPriceProductBase()->getPrice();
        $discount = $priceObject->getPriceProductDiscount()->getValue();
        $tax = $priceObject->getPriceProductTax()->getTaxSlab()->getRateOfTax();

        return $basePrice - $discount * (1 - $tax / 100);

    }
}