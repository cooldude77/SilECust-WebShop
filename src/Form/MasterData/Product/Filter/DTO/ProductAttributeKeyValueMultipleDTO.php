<?php

namespace App\Form\MasterData\Product\Filter\DTO;

class ProductAttributeKeyValueMultipleDTO
{

    public array $attributeKeyValues;

    public function add(ProductAttributeKeyValueDTO $dto)
    {
        $this->attributeKeyValues[] = $dto;
    }

    public function getAttributeKeyValues(): array
    {
        return $this->attributeKeyValues;
    }

}