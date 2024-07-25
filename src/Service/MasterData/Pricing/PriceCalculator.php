<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\PriceProductBase;
use App\Entity\PriceProductDiscount;
use App\Entity\Product;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Repository\PriceProductBaseRepository;
use App\Repository\PriceProductDiscountRepository;
use App\Repository\PriceProductTaxRepository;
use App\Repository\TaxSlabRepository;

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


        return $basePrice->getPrice() - ($discount != null ? $discount->getValue() : 0);

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
     * @param Product $product
     *
     * @return float
     * @throws PriceProductBaseNotFound|PriceProductTaxNotFound
     */
    public function calculatePriceWithTax(Product $product, Currency $currency, Country $country
    ): float {


        $basePrice = $this->priceProductBaseRepository->findOneBy(
            ['product' => $product, 'currency' => $currency]
        );
        if ($basePrice == null) {
            throw new PriceProductBaseNotFound($product);
        }

        $discount = $this->priceProductDiscountRepository->findOneBy(['product' => $product]);

        $taxSlab = $this->taxSlabRepository->findOneBy(['country'=>$country]) ;
        $tax = $this->priceProductTaxRepository->findOneBy(
            ['product' => $product, 'taxSlab'=>$taxSlab]
        );
        if ($tax == null) {
            throw new PriceProductTaxNotFound($product, $country);
        }

        return ($basePrice->getPrice() - $discount->getValue()) * (1 + $tax->getTaxSlab()
                ->getRateOfTax() /
                100);

    }
}