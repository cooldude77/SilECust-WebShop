<?php

namespace App\Service\Location\Mapper\City;

use App\Entity\City;
use App\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use App\Repository\CityRepository;
use App\Repository\StateRepository;

readonly class CityDTOMapper
{

    public function __construct(private CityRepository $cityRepository,
        private StateRepository                        $stateRepository
    ) {

    }

    public function mapToEntityForCreate(CityDTO $cityDTO): City
    {
        $city = $this->cityRepository->create($this->stateRepository->find($cityDTO->stateId));

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

    public function mapToDTOForEdit(City $city): CityDTO
    {
        $cityDTO = new CityDTO();
        $cityDTO->id = $city->getId();
        $cityDTO->name = $city->getName();
        $cityDTO->code = $city->getCode();
        $cityDTO->stateId = $city->getState()->getId();

        return $cityDTO;
    }
}