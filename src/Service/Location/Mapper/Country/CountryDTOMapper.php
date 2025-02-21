<?php

namespace Silecust\WebShop\Service\Location\Mapper\Country;

use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\Country\DTO\CountryDTO;
use Silecust\WebShop\Repository\CountryRepository;

class CountryDTOMapper
{
    public function __construct(private readonly CountryRepository $countryRepository)
    {
    }

    public function mapToEntityForCreate(CountryDTO $countryDTO): Country
    {
        $country = $this->countryRepository->create();

        $country->setCode($countryDTO->code);
        $country->setName($countryDTO->name);

        return $country;
    }

    public function mapToEntityForEdit(CountryDTO $countryDTO): Country
    {
        $country = $this->countryRepository->find($countryDTO->id);

        $country->setCode($countryDTO->code);
        $country->setName($countryDTO->name);

        return $country;
    }

    public function mapToDTOForEdit(Country $country): CountryDTO
    {
        $countryDTO = new CountryDTO();
        $countryDTO->id = $country->getId();
        $countryDTO->name = $country->getName();
        $countryDTO->code = $country->getCode();

        return $countryDTO;
    }
}