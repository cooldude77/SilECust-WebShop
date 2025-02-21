<?php

namespace Silecust\WebShop\Form\Finance\TaxSlab\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TaxSlabDTO
{

    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\_\s]/',
        message: 'Only characters and numbers are allowed',
        match: true
    )]
    public ?string $name = null;

    /**
     * @var string|null
     */

    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    public ?string $description = null;

    public ?int $countryId = 0;

    public ?int $rateOfTax = 0;

    #[Assert\GreaterThan(
        value: 0,
        groups: ['edit']
    )]
    public ?int $id = 0;
}