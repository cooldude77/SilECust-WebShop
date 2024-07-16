<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\Product;
use App\Repository\PriceProductBaseRepository;
use App\Repository\PriceProductDiscountRepository;
use App\Repository\PriceProductTaxRepository;

readonly class PriceCalculator
{
    public function __construct(
        private PriceProductBaseRepository $priceProductBaseRepository,
        private PriceProductDiscountRepository $priceProductDiscountRepository,
        private PriceProductTaxRepository $priceProductTaxRepository
    ) {
    }

    public function getPriceObject(Product $product): PriceObject
    {

        $basePrice = $this->priceProductBaseRepository->findOneBy(['product' => $product]);

        $discount = $this->priceProductDiscountRepository->findOneBy(['product' => $product]);

        $tax = $this->priceProductTaxRepository->findOneBy(['product' => $product]);

        $priceObject = new PriceObject();

        $priceObject->setPriceProductBase($basePrice);
        $priceObject->setPriceProductDiscount($discount);
        $priceObject->setPriceProductTax($tax);


        return $priceObject;
    }

    public function calculatePrice(PriceObject $priceObject): float
    {

        $basePrice = $priceObject->getPriceProductBase()->getPrice();
        $discount = $priceObject->getPriceProductDiscount()->getValue();
        $tax = $priceObject->getPriceProductTax()->getTaxSlab()->getRateOfTax();

        return $basePrice - $discount * (1 - $tax / 100);

    }

}