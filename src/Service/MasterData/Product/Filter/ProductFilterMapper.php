<?php

namespace App\Service\MasterData\Product\Filter;

use App\Form\MasterData\Product\Filter\DTO\ProductAttributeKeyValueDTO;
use App\Form\MasterData\Product\Filter\DTO\ProductAttributeKeyValueMultipleDTO;

class ProductFilterMapper
{

    public function format(array $availableFilters): ProductAttributeKeyValueMultipleDTO
    {

        $productFilters = new ProductAttributeKeyValueMultipleDTO();
        /**
         * @var  $key
         * @var  $array
         */
        foreach ($availableFilters as $key => $array) {

            foreach ($array as $arr) {
                $productFilterDTO = new ProductAttributeKeyValueDTO();
                $productFilterDTO->idProductAttributeKey = $key;
                $productFilterDTO->idProductAttributeKeyValue = $arr['value']->getId();
                $productFilterDTO->name = $arr['value']->getProductAttributeKey()->getName();
                $productFilterDTO->value = $arr['value']->getValue();

                $productFilters->add($productFilterDTO);
            }
        }

        return $productFilters;
    }

    public function mapToKeyValuePair(ProductAttributeKeyValueMultipleDTO $attributeKeyValueMultipleDTO): array
    {
        $passableFilters = [];
        /** @var ProductAttributeKeyValueDTO $attributeKeyValue */
        foreach ($attributeKeyValueMultipleDTO->getAttributeKeyValues() as $attributeKeyValue)
            $passableFilters[$attributeKeyValue->idProductAttributeKey][] = $attributeKeyValue->idProductAttributeKeyValue;

        return $passableFilters;
    }
}