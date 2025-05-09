<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class CityDTO
{
    public ?int $id = null;
    /**
     * @var string|null
     */
    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 10,
        maxMessage: 'Length cannot exceed 10 characters'
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z]/',
        message: 'Only characters are allowed',
        match: true
    )]
    public ?string $code = null;
    public ?string $name = null;
    public ?string $stateId=null;
}