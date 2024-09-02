<?php

namespace App\Form\MasterData\Price\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PriceProductBaseDTO
{

    #[Assert\GreaterThan(
        value: 0,
        groups: ['edit']
    )]
    public int $id = 0;
    /**
     * @var float
     */
    #[Assert\GreaterThan(

        value: 0
    )]
    public float $price = 0;

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
    public int $currencyId = 0;
}