<?php

namespace Silecust\WebShop\Form\MasterData\Price\Tax\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class PriceProductTaxDTO
{
    public int $id = 0;
    /**
     * @var int
     */
    #[Assert\Range(
        notInRangeMessage: 'Tax must be between {{ min }}% and {{ max }}%',
        min: 1,
        max: 100
    )]
    public int $value = 0;
    /**
     * @var int
     */
    #[Assert\GreaterThan(

        value: 0
    )]
    public int $productId = 0;
    /**
     * @var int
     */
    #[Assert\GreaterThan(

        value: 0
    )]
    public int $taxSlabId = 0;
}