<?php

namespace Silecust\WebShop\Form\MasterData\Product\Attribute\Value\DTO;

/**
 * Note: We cannot completely create a DTO is not having a domain object
 * because Entity Type will not create a dropdown if we use just an int
 */
class ProductAttributeValueDTO
{
    public int $id = 0;
    public ?string $name =null;
    public ?string $value = null;
    public ?int $productAttributeId = 0;

}