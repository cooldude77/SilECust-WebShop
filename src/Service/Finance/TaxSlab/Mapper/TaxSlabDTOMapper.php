<?php

namespace App\Service\Finance\TaxSlab\Mapper;

use App\Entity\TaxSlab;
use App\Form\Finance\TaxSlab\DTO\TaxSlabDTO;
use App\Repository\CountryRepository;
use App\Repository\TaxSlabRepository;

readonly class TaxSlabDTOMapper
{
    public function __construct(private TaxSlabRepository $taxSlabRepository,
                                private CountryRepository $countryRepository)
    {
    }

    public function mapToEntityForCreate(TaxSlabDTO $taxSlabDTO): TaxSlab
    {


        $taxSlab = $this->taxSlabRepository->create(
            $this->countryRepository->find($taxSlabDTO->countryId)
        );

        $taxSlab->setName($taxSlabDTO->name);
        $taxSlab->setDescription($taxSlabDTO->description);

        return $taxSlab;
    }

    public function mapToEntityForEdit(TaxSlabDTO $taxSlabDTO): TaxSlab
    {

        $taxSlab = $this->taxSlabRepository->findOneBy(['id' => $taxSlabDTO->id]);

        $taxSlab->setName($taxSlabDTO->name);
        $taxSlab->setDescription($taxSlabDTO->description);
        $taxSlab->setRateOfTax($taxSlabDTO->rateOfTax);

        return $taxSlab;
    }

    public function mapToDtoFromEntity(?TaxSlab $taxSlab): TaxSlabDTO
    {
        $taxSlabDTO = new TaxSlabDTO();
        $taxSlabDTO->id = $taxSlab->getId();
        $taxSlabDTO->name = $taxSlab->getName();
        $taxSlabDTO->description = $taxSlab->getDescription();
        $taxSlabDTO->countryId = $taxSlab->getCountry()->getId();
        $taxSlabDTO->rateOfTax = $taxSlab->getRateOfTax();

        return $taxSlabDTO;

    }


}