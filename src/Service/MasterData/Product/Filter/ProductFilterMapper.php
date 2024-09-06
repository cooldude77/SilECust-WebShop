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
         *
         */
        foreach ($availableFilters as $key => $array) {

            foreach ($array as $arr) {
                $productFilterDTO = new ProductAttributeKeyValueDTO();
                $productFilterDTO->idProductAttributeKey = $key;
                $productFilterDTO->idProductAttributeKeyValue = $arr['value']->getId();
                $productFilterDTO->value = $arr['value']->getValue();

                $productFilters->add($productFilterDTO);
            }
        }

        return $productFilters;
    }
}