<?php

namespace App\Service\MasterData\Pricing;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\Product;
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
    private Country $country;
    private Currency $currency;

    public function __construct(private PriceCalculator $priceCalculator,
        private CountryRepository $countryRepository,
        private ProductRepository $productRepository,
        private CurrencyRepository $currencyRepository,
        #[Autowire(param: 'silecust.default_country')]
        string $countryCode
    ) {


        $this->country = $this->countryRepository->findOneBy(['code' => $countryCode]);
        $this->currency = $this->currencyRepository->findOneBy(['country' => $this->country]);

    }

    /**
     * @throws PriceProductBaseNotFound
     */
    public function getPriceWithoutTax(int $productId): float {
        $product = $this->productRepository->find($productId);
        return $this->priceCalculator->calculatePriceWithoutTax(
            $product,
            $this->currency
        );
    }

    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function getPriceWithTax(int $productId): float {
        $product = $this->productRepository->find($productId);
        return $this->priceCalculator->calculatePriceWithTax(
            $product,
            $this->currency,
            $this->country
        );
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }
}