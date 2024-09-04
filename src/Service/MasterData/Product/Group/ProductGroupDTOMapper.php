<?php

namespace App\Service\MasterData\Product\Group;

use App\Entity\ProductGroup;
use App\Form\MasterData\Product\Group\DTO\ProductGroupDTO;
use App\Repository\ProductGroupRepository;

class ProductGroupDTOMapper
{

    public function __construct(private readonly ProductGroupRepository $productGroupRepository)
    {
    }

    public function mapDtoToEntityForCreate(ProductGroupDTO $productGroupDTO): ProductGroup
    {
        $group = $this->productGroupRepository->create();

        $group->setName($productGroupDTO->name);
        $group->setDescription($productGroupDTO->description);

        return $group;

    }

    public function mapDtoToEntityForUpdate(ProductGroupDTO $productGroupDTO
    ): ProductGroup
    {

        $productGroupEntity = $this->productGroupRepository->find($productGroupDTO->id);
        $productGroupEntity->setName($productGroupDTO->name);
        $productGroupEntity->setDescription($productGroupDTO->description);


        return $productGroupEntity;

    }

    public function mapToDtoFromEntityForEdit(ProductGroup $productGroup): ProductGroupDTO
    {
        $productGroupDTO = new ProductGroupDTO();
        $productGroupDTO->id = $productGroup->getId();
        $productGroupDTO->name = $productGroup->getName();
        $productGroupDTO->description = $productGroup->getDescription();
        return $productGroupDTO;
    }

}