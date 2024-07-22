<?php

namespace App\Service\MasterData\Pricing\Item;

use App\Entity\Product;
use App\Repository\PriceProductBaseRepository;
use App\Repository\PriceProductDiscountRepository;
use App\Repository\PriceProductTaxRepository;

readonly class PriceBreakUp
{
    public function __construct(
        private PriceProductBaseRepository $priceProductBaseRepository,
        private PriceProductDiscountRepository $priceProductDiscountRepository,
        private PriceProductTaxRepository $priceProductTaxRepository
    ) {
    }

    public function getPriceObject(Product $product): PriceBreakUpObject
    {

        $basePrice = $this->priceProductBaseRepository->findOneBy(['product' => $product]);

        $discount = $this->priceProductDiscountRepository->findOneBy(['product' => $product]);

        $tax = $this->priceProductTaxRepository->findOneBy(['product' => $product]);

        $priceObject = new PriceBreakUpObject();

        $priceObject->setPriceProductBase($basePrice);
        $priceObject->setPriceProductDiscount($discount);
        $priceObject->setPriceProductTax($tax);


        return $priceObject;
    }



}