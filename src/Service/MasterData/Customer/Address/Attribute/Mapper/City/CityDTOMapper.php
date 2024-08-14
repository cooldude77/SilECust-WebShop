<?php

namespace App\Service\MasterData\Customer\Address\Attribute\Mapper\City;

use App\Entity\City;
use App\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use App\Repository\CityRepository;
use App\Repository\StateRepository;

class CityDTOMapper
{

    public function __construct(private readonly CityRepository $cityRepository,
        private readonly StateRepository $stateRepository
    ) {

    }

    public function mapToEntityForCreate(CityDTO $cityDTO): City
    {
        $city = new City();

        $city->setCode($cityDTO->code);
        $city->setName($cityDTO->name);
        $city->setState($this->stateRepository->find($cityDTO->stateId));

        return $city;
    }

    public function mapToEntityForEdit(CityDTO $cityDTO): City
    {
        $city = $this->cityRepository->find($cityDTO->id);

        $city->setCode($cityDTO->code);
        $city->setName($cityDTO->name);
        $city->setState($this->stateRepository->find($cityDTO->stateId));

        return $city;
    }
}