<?php

namespace App\Service\MasterData\Product\Attribute;

use App\Entity\ProductAttributeKey;
use App\Entity\ProductAttributeKeyType;
use App\Form\MasterData\Product\Attribute\DTO\ProductAttributeKeyDTO;
use App\Repository\ProductAttributeKeyRepository;
use App\Repository\ProductAttributeKeyTypeRepository;

class ProductAttributeKeyDTOMapper
{

    public function __construct(private ProductAttributeKeyTypeRepository $ProductAttributeKeyTypeRepository,
        private ProductAttributeKeyRepository                             $ProductAttributeKeyRepository
    ) {
    }

    public function mapDtoToEntity(ProductAttributeKeyDTO $ProductAttributeKeyDTO
    ): ProductAttributeKey {
        $attribute = $this->ProductAttributeKeyRepository->create();
        /** @var ProductAttributeKeyType $type */
        $type = $this->ProductAttributeKeyTypeRepository->findOneBy(
            ['id' => $ProductAttributeKeyDTO->ProductAttributeKeyTypeId]
        );

        $attribute->setName($ProductAttributeKeyDTO->name);
        $attribute->setDescription($ProductAttributeKeyDTO->description);

        $attribute->setProductAttributeKeyType($type);

        return $attribute;

    }

    public function mapDtoToEntityForEdit(ProductAttributeKeyDTO $ProductAttributeKeyDTO,
                                          ProductAttributeKey $ProductAttributeKeyEntity
    ) {


        $ProductAttributeKeyEntity->setName($ProductAttributeKeyDTO->name);
        $ProductAttributeKeyEntity->setDescription($ProductAttributeKeyDTO->description);

        return $ProductAttributeKeyEntity;


    }

}