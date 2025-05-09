<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\Country\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CountryDTO
{

    public ?int $id = null;
    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 2,
        maxMessage: 'Length cannot exceed 2 characters'
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z]/',
        message: 'Only characters are allowed',
        match: true
    )]
    public ?string $code = null;

    public ?string $name = null;

}