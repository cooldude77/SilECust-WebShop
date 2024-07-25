<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\Country;
use App\Entity\Currency;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\ProductRepository;
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

        $country = $this->countryRepository->findOneBy(['code' => $this->countryCode]);
        $currency = $this->currencyRepository->findOneBy(['country' => $country]);

        $product = $this->productRepository->find($productId);
        return $this->priceCalculator->calculatePriceWithoutTax(
            $product, $currency
        );
    }

    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function getPriceWithTax(int $productId): float
    {

        $country = $this->countryRepository->findOneBy(['code' => $this->countryCode]);
        $currency = $this->currencyRepository->findOneBy(['country' => $country]);

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
}