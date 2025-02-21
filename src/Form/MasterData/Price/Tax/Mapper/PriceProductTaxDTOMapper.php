<?php

namespace Silecust\WebShop\Form\MasterData\Price\Tax\Mapper;

use Silecust\WebShop\Entity\PriceProductTax;
use Silecust\WebShop\Form\MasterData\Price\Tax\DTO\PriceProductTaxDTO;
use Silecust\WebShop\Repository\PriceProductTaxRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Repository\TaxSlabRepository;

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