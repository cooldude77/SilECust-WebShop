<?php

namespace App\Form\MasterData\Customer\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CustomerDTO
{
    public ?int $id = -1;
    public ?string $firstName = null;
    public ?string $middleName = null;
    public ?string $lastName = null;
    public ?string $givenName = null;
    public ?int $salutationId = 0;

    public ?string $email = null;

    public ?string $phoneNumber = null;
    public ?string $plainPassword = null;

}