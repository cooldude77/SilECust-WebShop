<?php

namespace Silecust\WebShop\Service\MasterData\Price;

use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Exception\MasterData\Pricing\DefaultCountryNotSet;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Repository\CountryRepository;
use Silecust\WebShop\Repository\CurrencyRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Transaction\Order\PriceObject;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 *
 */
readonly class PriceByCountryCalculator implements PriceByCountryCalculatorInterface
{
    /**
     * @throws DefaultCountryNotSet
     */
    public function __construct(
        private PriceCalculator    $priceCalculator,
        private CountryRepository  $countryRepository,
        private ProductRepository  $productRepository,
        private CurrencyRepository $currencyRepository,
        #[Autowire(param: 'silecust.default_country')]
        private string             $countryCode
    ) {
        if($this->countryCode == null)
             throw new DefaultCountryNotSet();
    }


    /**
     * @throws PriceProductBaseNotFound
     */
    public function getPriceWithoutTax(int $productId): float
    {

        list($country, $currency) = $this->setCountryAndCurrency();

        $product = $this->productRepository->find($productId);
        return $this->priceCalculator->calculatePriceWithoutTax(
            $product, $currency
        );
    }

    /**
     * @return array
     */
    public function setCountryAndCurrency(): array
    {
        $country = $this->countryRepository->findOneBy(['code' => $this->countryCode]);
        $currency = $this->currencyRepository->findOneBy(['country' => $country]);
        return array($country, $currency);
    }

    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function getPriceWithTax(int $productId): float
    {

        list($country, $currency) = $this->setCountryAndCurrency();

        $product = $this->productRepository->find($productId);
        return $this->priceCalculator->calculatePriceWithTax(
            $product, $currency, $country
        );
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {

        $country = $this->countryRepository->findOneBy(['code' => $this->countryCode]);
        return $this->currencyRepository->findOneBy(['country' => $country]);
    }

    /**
     * @throws PriceProductTaxNotFound
     * @throws PriceProductBaseNotFound
     */
    public function getPriceObject(OrderItem $orderItem
    ): PriceObject {
        list($country, $currency) = $this->setCountryAndCurrency();
        return $this->priceCalculator->getPriceObject(
            $orderItem->getProduct(), $country, $currency
        );


    }
}