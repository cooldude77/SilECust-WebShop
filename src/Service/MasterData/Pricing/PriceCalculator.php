<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\PriceProductBase;
use App\Entity\PriceProductDiscount;
use App\Entity\PriceProductTax;
use App\Entity\Product;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Repository\PriceProductBaseRepository;
use App\Repository\PriceProductDiscountRepository;
use App\Repository\PriceProductTaxRepository;
use App\Repository\TaxSlabRepository;
use App\Service\Transaction\Order\PriceObject;

/**
 *
 */
readonly class PriceCalculator
{
    /**
     * @param PriceProductBaseRepository     $priceProductBaseRepository
     * @param PriceProductDiscountRepository $priceProductDiscountRepository
     * @param PriceProductTaxRepository      $priceProductTaxRepository
     */
    public function __construct(private PriceProductBaseRepository $priceProductBaseRepository,
        private PriceProductDiscountRepository $priceProductDiscountRepository,
        private PriceProductTaxRepository $priceProductTaxRepository,
        private TaxSlabRepository $taxSlabRepository
    ) {
    }

    /**
     * @throws PriceProductBaseNotFound
     */
    public function calculatePriceWithoutTax(Product $product, Currency $currency): float
    {

        $basePrice = $this->getBasePrice($product, $currency);

        $discount = $this->getDiscount($product);


        $discountRate = $discount != null ? $discount->getValue() : 0;

        return $basePrice->getPrice() * (1-$discountRate/100);

    }

    /**
     * @param Product $product
     *
     * @return float
     * @throws PriceProductBaseNotFound|PriceProductTaxNotFound
     */
    public function calculatePriceWithTax(Product $product, Currency $currency, Country $country
    ): float {


        $basePrice = $this->getBasePrice($product,$currency);

        $discount = $this->getDiscount($product);

        $tax = $this->getTaxRate($product, $country);

        $discountRate = $discount != null ? $discount->getValue() : 0;

        $priceAfterDiscount = $basePrice->getPrice() * (1-$discountRate/100);

        return $priceAfterDiscount * (1 + $tax->getTaxSlab()->getRateOfTax() / 100);

    }



    /**
     * @param Product $product
     *
     * @return PriceProductBase
     * @throws PriceProductBaseNotFound
     */
    public function getBasePrice(Product $product, Currency $currency): PriceProductBase
    {
        $basePrice = $this->priceProductBaseRepository->findOneBy(
            [
                'product' => $product,
                'currency' => $currency
            ]
        );
        if ($basePrice == null) {
            throw new PriceProductBaseNotFound($product);
        }
        return $basePrice;
    }

    /**
     * @param Product $productpo
     *
     * @return PriceProductDiscount|null
     */
    public function getDiscount(Product $product): ?PriceProductDiscount
    {
        return $this->priceProductDiscountRepository->findOneBy(['product' => $product]);
    }

    /**
     * @param Country $country
     * @param Product $product
     *
     * @return PriceProductTax
     * @throws PriceProductTaxNotFound
     */
    public function getTaxRate(Product $product,Country $country): \App\Entity\PriceProductTax
    {
        $taxSlabs = $this->taxSlabRepository->findBy(['country' => $country]);
        $tax = $this->priceProductTaxRepository->findProductByTaxSlabs(
             $product,$taxSlabs
        );
        if ($tax == null) {
            throw new PriceProductTaxNotFound($product, $country);
        }
        return $tax[0];
    }

    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function getPriceObject(Product $product,  Country $country,Currency $currency): PriceObject
    {

        $priceObject = new PriceObject($this->getBasePrice($product,$currency)->getPrice());

        $priceObject->setDiscount($this->getDiscount($product)->getValue());
        $priceObject->setTaxRate($this->getTaxRate($product,$country)->getTaxSlab()->getRateOfTax
        ());

        return $priceObject;
    }
}