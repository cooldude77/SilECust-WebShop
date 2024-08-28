<?php

namespace App\Service\Location\Mapper\PostalCode;

use App\Entity\PostalCode;
use App\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO\PostalCodeDTO;
use App\Repository\CityRepository;
use App\Repository\PostalCodeRepository;

class PostalCodeDTOMapper
{
    public function __construct(private readonly PostalCodeRepository $postalCodeRepository,
                                private readonly CityRepository       $cityRepository)
    {
    }

    public function mapToEntityForCreate(PostalCodeDTO $postalCodeDTO): PostalCode
    {
        $postalCode = $this->postalCodeRepository->create($this->cityRepository->find($postalCodeDTO->cityId));

        $postalCode->setCity($this->cityRepository->find($postalCodeDTO->cityId));
        $postalCode->setPostalCode($postalCodeDTO->postalCode);

        return $postalCode;
    }

    public function mapToEntityForEdit(PostalCodeDTO $postalCodeDTO): PostalCode
    {
        $postalCode = $this->postalCodeRepository->find($postalCodeDTO->id);
        $postalCode->setCity($this->cityRepository->find($postalCodeDTO->cityId));
        $postalCode->setPostalCode($postalCodeDTO->postalCode);

        return $postalCode;
    }

    public function mapToDTOForEdit(PostalCode $postalCode): PostalCodeDTO
    {
        $postalCodeDTO = new PostalCodeDTO();
        $postalCodeDTO->id = $postalCode->getId();
        $postalCodeDTO->postalCode = $postalCode->getPostalCode();
        $postalCodeDTO->cityId = $postalCode->getCity()->getId();

        return $postalCodeDTO;
    }
}