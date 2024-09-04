<?php

namespace App\Service\MasterData\Product\Attribute;

use App\Entity\ProductAttributeKey;
use App\Entity\ProductAttributeKeyType;
use App\Form\MasterData\Product\Attribute\DTO\ProductAttributeKeyDTO;
use App\Repository\ProductAttributeKeyRepository;
use App\Repository\ProductAttributeKeyTypeRepository;

class ProductAttributeKeyDTOMapper
{

    public function __construct(private ProductAttributeKeyTypeRepository $productAttributeKeyTypeRepository,
                                private ProductAttributeKeyRepository     $productAttributeKeyRepository
    )
    {
    }

    public function mapDtoToEntity(ProductAttributeKeyDTO $ProductAttributeKeyDTO
    ): ProductAttributeKey
    {
        $attribute = $this->productAttributeKeyRepository->create();
        /** @var ProductAttributeKeyType $type */
        $type = $this->productAttributeKeyTypeRepository->findOneBy(
            ['id' => $ProductAttributeKeyDTO->ProductAttributeKeyTypeId]
        );

        $attribute->setName($ProductAttributeKeyDTO->name);
        $attribute->setDescription($ProductAttributeKeyDTO->description);

        $attribute->setProductAttributeKeyType($type);

        return $attribute;

    }

    public function mapDtoToEntityForEdit(ProductAttributeKeyDTO $ProductAttributeKeyDTO): ProductAttributeKey
    {

        $ProductAttributeKeyEntity = $this->productAttributeKeyRepository->find($ProductAttributeKeyDTO->id);

        $ProductAttributeKeyEntity->setName($ProductAttributeKeyDTO->name);
        $ProductAttributeKeyEntity->setDescription($ProductAttributeKeyDTO->description);

        return $ProductAttributeKeyEntity;


    }

    public function mapDtoFromEntityForEdit(ProductAttributeKey $productAttributeKey): ProductAttributeKeyDTO
    {
        $productAttributeKeyDTO = new ProductAttributeKeyDTO();
        $productAttributeKeyDTO->id = $productAttributeKey->getId();
        $productAttributeKeyDTO->name = $productAttributeKey->getName();
        $productAttributeKeyDTO->description = $productAttributeKey->getDescription();
        return $productAttributeKeyDTO;
    }

}