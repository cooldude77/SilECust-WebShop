<?php

namespace App\Form\MasterData\Product\Filter\DTO;

class ProductAttributeKeyValueDTO
{

    public int $idProductAttributeKey = 0;
    public int $idProductAttributeKeyValue = 0;
    public bool $isSelected = false;
    public ?string $value = null;

}