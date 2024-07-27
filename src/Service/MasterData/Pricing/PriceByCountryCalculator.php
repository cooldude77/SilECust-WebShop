<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\OrderItem;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ProductRepository;
use App\Service\Transaction\Order\PriceObject;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 *
 */
readonly class PriceByCountryCalculator
{
    public function __construct(private PriceCalculator $priceCalculator,
        private CountryRepository $countryRepository, private ProductRepository $productRepository,
        private CurrencyRepository $currencyRepository,
        #[Autowire(param: 'silecust.default_country')]
        private string $countryCode
    ) {


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