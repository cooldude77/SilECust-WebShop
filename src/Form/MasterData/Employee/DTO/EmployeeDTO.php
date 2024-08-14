<?php

namespace App\Form\MasterData\Employee\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeDTO
{
    public ?int $id = -1;
    public ?string $firstName = null;
    public ?string $middleName = null;
    public ?string $lastName = null;
    public ?string $givenName = null;
    public ?int $salutationId = null;

    public ?string $email = null;

    public ?string $phoneNumber = null;
    public ?string $plainPassword = null;

}