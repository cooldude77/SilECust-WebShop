<?php

namespace Silecust\WebShop\Service\MasterData\Product\Attribute;

use Silecust\WebShop\Entity\ProductAttribute;
use Silecust\WebShop\Entity\ProductAttributeType;
use Silecust\WebShop\Form\MasterData\Product\Attribute\DTO\ProductAttributeDTO;
use Silecust\WebShop\Repository\ProductAttributeRepository;
use Silecust\WebShop\Repository\ProductAttributeTypeRepository;

class ProductAttributeDTOMapper
{

    public function __construct(private ProductAttributeTypeRepository $productAttributeTypeRepository,
        private ProductAttributeRepository $productAttributeRepository
    ) {
    }

    public function mapDtoToEntity(ProductAttributeDTO $productAttributeDTO
    ): ProductAttribute {
        $attribute = $this->productAttributeRepository->create();
        /** @var ProductAttributeType $type */
        $type = $this->productAttributeTypeRepository->findOneBy(
            ['id' => $productAttributeDTO->productAttributeTypeId]
        );

        $attribute->setName($productAttributeDTO->name);
        $attribute->setDescription($productAttributeDTO->description);

        $attribute->setProductAttributeType($type);

        return $attribute;

    }

    public function mapDtoToEntityForEdit(ProductAttributeDTO $productAttributeDTO,
        ProductAttribute $productAttributeEntity
    ) {


        $productAttributeEntity->setName($productAttributeDTO->name);
        $productAttributeEntity->setDescription($productAttributeDTO->description);

        return $productAttributeEntity;


    }

}