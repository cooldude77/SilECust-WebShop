<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class PostalCodeDTO
{
    public ?int $id = null;
    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 10,
        maxMessage: 'Length cannot exceed 10 characters'
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9]/',
        message: 'Only characters are allowed',
        match: true
    )]
    public ?string $postalCode = null;
    public ?string $name = null;
    public ?string $cityId=null;
}