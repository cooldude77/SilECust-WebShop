<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Entity\PriceProductDiscount;
use Silecust\WebShop\Entity\PriceProductTax;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Entity\TaxSlab;
use Silecust\WebShop\Factory\PriceProductBaseFactory;
use Silecust\WebShop\Factory\PriceProductDiscountFactory;
use Silecust\WebShop\Factory\PriceProductTaxFactory;
use Silecust\WebShop\Factory\TaxSlabFactory;
use Zenstruck\Foundry\Proxy;

trait PriceFixture
{

    public PriceProductBase|Proxy $priceProductBaseA;
    public PriceProductBase|Proxy $priceProductBaseB;
    public float $priceValueOfProductA = 100;
    public float $priceValueOfProductB = 200;

    public PriceProductDiscount|Proxy $productDiscountA;
    public PriceProductDiscount|Proxy $productDiscountB;
    public float $discountValueOfProductA = 10;
    public float $discountValueOfProductB = 20;

    public PriceProductTax|Proxy $productTaxA;
    public PriceProductTax|Proxy $productTaxB;

    public TaxSlab|Proxy $taxSlabForProductA;
    public TaxSlab|Proxy $taxSlabForProductB;
    public float $taxRateOfProductA = 10;
    public float $taxRateOfProductB = 20;

    /** Total amount of 1st item is
     *  (100*(1-10/100)) = 90 Without Tax  = 90* (1+10/100) = 99 With Tax
     *  (200*(1-20/100)) = 160 Without Tax = 160* (1+20/100) = 192
     * Total cart value before shipping  =
     * 99*10 +192* 20 = 4830
     */

    function createPriceFixtures(Proxy|Product $productA, Proxy|Product $productB,
        Proxy|Currency $currency
    ): void {

        $this->priceProductBaseA = PriceProductBaseFactory::createOne(['product' => $productA,
                                                                       'currency' => $currency,
                                                                       'price' => $this->priceValueOfProductA]
        );
        $this->priceProductBaseB = PriceProductBaseFactory::createOne(['product' => $productB,
                                                                       'currency' => $currency,
                                                                       'price' => $this->priceValueOfProductB]
        );
        $this->productDiscountA = PriceProductDiscountFactory::createOne(
            ['product' => $productA,
             'currency' => $currency,
             'value' =>
                 $this->discountValueOfProductA]
        );
        $this->productDiscountB = PriceProductDiscountFactory::createOne(
            ['product' => $productB,
             'currency' => $currency,
             'value' =>
                 $this->discountValueOfProductB]
        );

        $this->taxSlabForProductA = TaxSlabFactory::createOne
        (
            ['country' => $this->country, 'rateOfTax' => $this->taxRateOfProductA]
        );
        $this->taxSlabForProductB = TaxSlabFactory::createOne(
            ['country' => $this->country, 'rateOfTax' => $this->taxRateOfProductB]
        );

        $this->productTaxA = PriceProductTaxFactory::createOne(
            ['product' => $productA,
             'taxSlab' =>
                 $this->taxSlabForProductA]
        );
        $this->productTaxB = PriceProductTaxFactory::createOne(
            ['product' => $productB,
             'taxSlab' =>
                 $this->taxSlabForProductB]
        );

    }

}