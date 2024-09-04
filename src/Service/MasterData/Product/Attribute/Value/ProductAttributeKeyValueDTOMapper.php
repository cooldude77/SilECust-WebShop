<?php

namespace App\Service\MasterData\Product\Attribute\Value;

use App\Entity\ProductAttributeKey;
use App\Entity\ProductAttributeKeyValue;
use App\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeKeyValueDTO;
use App\Repository\ProductAttributeKeyRepository;
use App\Repository\ProductAttributeKeyValueRepository;

readonly class ProductAttributeKeyValueDTOMapper
{

    public function __construct(private ProductAttributeKeyRepository $ProductAttributeKeyRepository,
        private ProductAttributeKeyValueRepository                       $ProductAttributeKeyValueRepository
    ) {
    }

    public function mapDtoToEntityForCreate(ProductAttributeKeyValueDTO $ProductAttributeKeyValueDTO
    ): ProductAttributeKeyValue {
        /** @var ProductAttributeKey $ProductAttributeKey */

        $ProductAttributeKey = $this->ProductAttributeKeyRepository->findOneBy(
            ['id' => $ProductAttributeKeyValueDTO->ProductAttributeKeyId]
        );

        $attribute = $this->ProductAttributeKeyValueRepository->create($ProductAttributeKey);

        $attribute->setName($ProductAttributeKeyValueDTO->name);
        $attribute->setValue($ProductAttributeKeyValueDTO->value);

        $attribute->setProductAttributeKey($ProductAttributeKey);

        return $attribute;

    }

    public function mapDtoToEntityForUpdate(ProductAttributeKeyValueDTO $ProductAttributeKeyValueDTO,
                                            ProductAttributeKeyValue $ProductAttributeKeyValueEntity): ProductAttributeKeyValue
    {

        $ProductAttributeKeyValueEntity->setName($ProductAttributeKeyValueDTO->name);
        $ProductAttributeKeyValueEntity->setValue($ProductAttributeKeyValueDTO->value);

        return $ProductAttributeKeyValueEntity;
    }

}