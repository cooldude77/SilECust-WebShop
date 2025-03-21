<?php

namespace Silecust\WebShop\Service\Location\Mapper\PostalCode;

use Silecust\WebShop\Entity\PostalCode;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO\PostalCodeDTO;
use Silecust\WebShop\Repository\CityRepository;
use Silecust\WebShop\Repository\PostalCodeRepository;

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
        $postalCode->setName($postalCodeDTO->name);
        $postalCode->setCode($postalCodeDTO->code);

        return $postalCode;
    }

    public function mapToEntityForEdit(PostalCodeDTO $postalCodeDTO): PostalCode
    {
        $postalCode = $this->postalCodeRepository->find($postalCodeDTO->id);
        $postalCode->setCity($this->cityRepository->find($postalCodeDTO->cityId));
        $postalCode->setName($postalCodeDTO->name);

        return $postalCode;
    }

    public function mapToDTOForEdit(PostalCode $postalCode): PostalCodeDTO
    {
        $postalCodeDTO = new PostalCodeDTO();
        $postalCodeDTO->id = $postalCode->getId();
        $postalCodeDTO->name = $postalCode->getName();
        $postalCodeDTO->postalCode = $postalCode->getCode();
        $postalCodeDTO->cityId = $postalCode->getCity()->getId();

        return $postalCodeDTO;
    }
}