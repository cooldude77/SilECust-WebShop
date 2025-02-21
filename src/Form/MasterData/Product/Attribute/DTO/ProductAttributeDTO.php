<?php

namespace Silecust\WebShop\Form\MasterData\Product\Attribute\DTO;

/**
 * Note: We cannot completely create a DTO is not having a domain object
 * because Entity Type will not create a dropdown if we use just an int
 */
class ProductAttributeDTO
{
    public int $id = 0;
    public ?string $name =null;
    public ?string $description = null;
    public ?int $productAttributeTypeId = null;

}