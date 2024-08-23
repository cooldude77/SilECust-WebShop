<?php

namespace App\Form\MasterData\Price\Tax\Mapper;

use App\Entity\PriceProductTax;
use App\Form\MasterData\Price\Tax\DTO\PriceProductTaxDTO;
use App\Repository\PriceProductTaxRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxSlabRepository;

readonly class PriceProductTaxDTOMapper
{


    public function __construct(private ProductRepository         $productRepository,
                                private TaxSlabRepository         $taxSlabRepository,
                                private PriceProductTaxRepository $priceProductTaxRepository
    )
    {
    }

    public function mapDtoToEntityForCreate(PriceProductTaxDTO $priceProductTaxDTO): PriceProductTax
    {

        $product = $this->productRepository->findOneBy(['id' => $priceProductTaxDTO->productId]);
        $taxSlab = $this->taxSlabRepository->findOneBy(['id' => $priceProductTaxDTO->taxSlabId]);

        return $this->priceProductTaxRepository->create($product, $taxSlab);

    }

    public function mapDtoToEntityForEdit(PriceProductTaxDTO $priceProductTaxDTO): PriceProductTax
    {
        $priceProductTax = $this->priceProductTaxRepository->find($priceProductTaxDTO->id);
        $priceProductTax->setTaxSlab($this->taxSlabRepository->find($priceProductTaxDTO->taxSlabId));

        return $priceProductTax;
    }

    public function mapToDtoFromEntityForEdit(PriceProductTax $priceProductTax): PriceProductTaxDTO
    {

        $priceProductTaxDTO = new PriceProductTaxDTO();
        $priceProductTaxDTO->id = $priceProductTax->getId();
        $priceProductTaxDTO->productId = $priceProductTax->getProduct()->getId();
        $priceProductTaxDTO->taxSlabId = $priceProductTax->getTaxSlab()->getId();

        return $priceProductTaxDTO;
    }
}