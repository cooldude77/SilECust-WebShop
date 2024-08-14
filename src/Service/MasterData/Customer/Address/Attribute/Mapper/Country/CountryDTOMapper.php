<?php

namespace App\Service\MasterData\Customer\Address\Attribute\Mapper\Country;

use App\Entity\Country;
use App\Form\MasterData\Customer\Address\Attribute\Country\DTO\CountryDTO;

class CountryDTOMapper
{
    public function mapToEntityForCreate(CountryDTO $countryDTO): Country
    {
        $country = new Country();
        return $country;
    }

    public function mapToEntityForEdit(CountryDTO $countryDTO): Country
    {
        $country = new Country();
        return $country;
    }
}