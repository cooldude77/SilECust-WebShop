<?php

namespace App\Service\MasterData\Product\Type;

use App\Entity\ProductGroup;
use App\Form\MasterData\Product\Type\DTO\ProductTypeDTO;
use App\Repository\ProductGroupRepository;

class ProductTypeDTOMapper
{

    public function __construct(private readonly ProductGroupRepository $productTypeRepository)
    {
    }

    public function mapDtoToEntityForCreate(ProductTypeDTO $productTypeDTO): ProductGroup
    {
        $type = $this->productTypeRepository->create();

        $type->setName($productTypeDTO->name);
        $type->setDescription($productTypeDTO->value);

        return $type;

    }

    public function mapDtoToEntityForUpdate(ProductTypeDTO $productTypeDTO,
                                            ProductGroup $productTypeEntity
    ) {

        $productTypeEntity->setName($productTypeDTO->name);
        $productTypeEntity->setDescription($productTypeDTO->value);


        return $productTypeEntity;

    }

}