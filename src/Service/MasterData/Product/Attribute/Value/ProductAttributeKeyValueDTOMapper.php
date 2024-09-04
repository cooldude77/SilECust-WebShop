<?php

namespace App\Service\MasterData\Product\Attribute\Value;

use App\Entity\ProductAttributeKey;
use App\Entity\ProductAttributeKeyValue;
use App\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeKeyValueDTO;
use App\Repository\ProductAttributeKeyRepository;
use App\Repository\ProductAttributeKeyValueRepository;

readonly class ProductAttributeKeyValueDTOMapper
{

    public function __construct(private ProductAttributeKeyRepository      $productAttributeKeyRepository,
                                private ProductAttributeKeyValueRepository $productAttributeKeyValueRepository
    )
    {
    }

    public function mapDtoToEntityForCreate(ProductAttributeKeyValueDTO $productAttributeKeyValueDTO
    ): ProductAttributeKeyValue
    {
        /** @var ProductAttributeKey $productAttributeKey */

        $productAttributeKey = $this->productAttributeKeyRepository->findOneBy(
            ['id' => $productAttributeKeyValueDTO->ProductAttributeKeyId]
        );

        $attribute = $this->productAttributeKeyValueRepository->create($productAttributeKey);

        $attribute->setName($productAttributeKeyValueDTO->name);
        $attribute->setValue($productAttributeKeyValueDTO->value);

        $attribute->setProductAttributeKey($productAttributeKey);

        return $attribute;

    }

    public function mapDtoToEntityForUpdate(ProductAttributeKeyValueDTO $productAttributeKeyValueDTO,
    ): ProductAttributeKeyValue
    {
        $productAttributeKeyValueEntity = $this->productAttributeKeyValueRepository->find($productAttributeKeyValueDTO->id);
        $productAttributeKeyValueEntity->setName($productAttributeKeyValueDTO->name);
        $productAttributeKeyValueEntity->setValue($productAttributeKeyValueDTO->value);

        return $productAttributeKeyValueEntity;
    }

    public function mapDtoFromEntityForEdit(ProductAttributeKeyValue $productAttributeKeyValue): ProductAttributeKeyValueDTO
    {
        $productAttributeKeyValueDTO = new ProductAttributeKeyValueDTO();
        $productAttributeKeyValueDTO->id = $productAttributeKeyValue->getId();
        $productAttributeKeyValueDTO->name = $productAttributeKeyValue->getName();
        $productAttributeKeyValueDTO->value = $productAttributeKeyValue->getValue();
        return $productAttributeKeyValueDTO;
    }

}