<?php

namespace App\Tests\Fixtures;

use App\Entity\Currency;
use App\Entity\PriceProductBase;
use App\Entity\PriceProductDiscount;
use App\Entity\PriceProductTax;
use App\Entity\Product;
use App\Entity\TaxSlab;
use App\Factory\PriceProductBaseFactory;
use App\Factory\PriceProductDiscountFactory;
use App\Factory\PriceProductTaxFactory;
use App\Factory\TaxSlabFactory;
use Zenstruck\Foundry\Proxy;

trait PriceFixture
{

    public PriceProductBase|Proxy $priceProductBaseA;
    public PriceProductBase|Proxy $priceProductBaseB;
    public float $priceValueOfProductA = 100;
    public float $priceValueOfProductB = 200;

    public PriceProductDiscount|Proxy $productDiscountA;
    public PriceProductDiscount|Proxy $productDiscountB;
    public float $discountValueOfProductA = 100;
    public float $discountValueOfProductB = 200;

    public PriceProductTax|Proxy $productTaxA;
    public PriceProductTax|Proxy $productTaxB;
    public float $taxValueOfProductA = 100;
    public float $taxValueOfProductB = 200;

     public TaxSlab|Proxy $taxSlabForProductA;
    public TaxSlab|Proxy $taxSlabForProductB;
    public float $taxRateOfProductA = 100;
    public float $taxRateOfProductB = 200;

    
    public TaxSlab|Proxy $taxSlab;
    
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

        $this->taxSlabForProductA = TaxSlabFactory::createOne(['rateOfTax'=>10]);
        $this->taxSlabForProductB = TaxSlabFactory::createOne(['rateOfTax'=>20]);
        
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