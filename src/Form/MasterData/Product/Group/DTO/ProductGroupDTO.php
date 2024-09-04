<?php

namespace App\Form\MasterData\Product\Group\DTO;

/**
 * Note: We cannot completely create a DTO is not having a domain object
 * because Entity Group will not create a dropdown if we use just an int
 */
class ProductGroupDTO
{
    public int $id = 0;
    public ?string $name =null;
    public ?string $description = null;

}