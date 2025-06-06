<?php

namespace Silecust\WebShop\Service\Finance\Currency\Mapper;

use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Form\Finance\Currency\DTO\CurrencyDTO;
use Silecust\WebShop\Repository\CountryRepository;
use Silecust\WebShop\Repository\CurrencyRepository;

readonly class CurrencyDTOMapper
{
    public function __construct(private CurrencyRepository $currencyRepository,
                                private CountryRepository  $countryRepository)
    {
    }

    public function mapToEntityForCreate(CurrencyDTO $currencyDTO): Currency
    {


        $currency = $this->currencyRepository->create(
            $this->countryRepository->find($currencyDTO->countryId)
        );

        $currency->setCode($currencyDTO->code);
        $currency->setDescription($currencyDTO->description);
        $currency->setSymbol($currencyDTO->symbol);

        return $currency;
    }

    public function mapToEntityForEdit(CurrencyDTO $currencyDTO): Currency
    {

        $currency = $this->currencyRepository->findOneBy(['id' => $currencyDTO->id]);

        $currency->setCode($currencyDTO->code);
        $currency->setDescription($currencyDTO->description);
        $currency->setSymbol($currencyDTO->symbol);
        $currency->setCountry($this->countryRepository->find($currencyDTO->countryId));

        return $currency;
    }

    public function mapToDtoFromEntity(?Currency $currency): CurrencyDTO
    {
        $currencyDTO = new CurrencyDTO();
        $currencyDTO->id = $currency->getId();
        $currencyDTO->code = $currency->getCode();
        $currencyDTO->description = $currency->getDescription();
        $currencyDTO->symbol = $currency->getSymbol();

        $currencyDTO->countryId = $currency->getCountry()->getId();

        return $currencyDTO;

    }


}