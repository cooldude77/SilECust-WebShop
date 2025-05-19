<?php

namespace Silecust\WebShop\Form\MasterData\Employee\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeDTO
{
    public ?int $id = -1;
    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255 characters'
    )]
    public ?string $firstName = null;
    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255 characters'
    )]
    public ?string $middleName = null;
    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255 characters'
    )]
    public ?string $lastName = null;

    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255 characters'
    )]
    public ?string $givenName = null;
    public ?int $salutationId = null;
    /**
     * @var string|null
     */
    #[Assert\Email]
    public ?string $email = null;

    public ?string $phoneNumber = null;
    public ?string $plainPassword = null;

}