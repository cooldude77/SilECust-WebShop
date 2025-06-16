<?php

namespace Silecust\WebShop\Service\MasterData\Price;

use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Entity\PriceProductDiscount;
use Silecust\WebShop\Entity\PriceProductTax;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Repository\PriceProductBaseRepository;
use Silecust\WebShop\Repository\PriceProductDiscountRepository;
use Silecust\WebShop\Repository\PriceProductTaxRepository;
use Silecust\WebShop\Repository\TaxSlabRepository;
use Silecust\WebShop\Service\Transaction\Order\PriceObject;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 */
readonly class PriceCalculator
{
    /**
     * @param PriceProductBaseRepository $priceProductBaseRepository
     * @param PriceProductDiscountRepository $priceProductDiscountRepository
     * @param PriceProductTaxRepository $priceProductTaxRepository
     * @param TaxSlabRepository $taxSlabRepository
     */
    public function __construct(
        private PriceProductBaseRepository $priceProductBaseRepository,
        private PriceProductDiscountRepository $priceProductDiscountRepository,
        private PriceProductTaxRepository $priceProductTaxRepository,
        private TaxSlabRepository          $taxSlabRepository,
        private SerializerInterface        $serializer
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
     * @param Product $product
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
    public function getTaxRate(Product $product,Country $country): \Silecust\WebShop\Entity\PriceProductTax
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


        $priceObject->setBasePriceArray(json_decode(
                $this->serializer->serialize($this->getBasePrice($product, $currency), 'json'), true)
        );
        $priceObject->setDiscountArray(
            json_decode(
                $this->serializer->serialize($this->getDiscount($product, $currency), 'json'),
                true)
        );
        $priceObject->setTaxRateArray(
            json_decode(
                $this->serializer->serialize($this->getTaxRate($product, $country),
                    'json'),
                true));

        return $priceObject;
    }
}