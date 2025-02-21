<?php

namespace Silecust\WebShop\Form\Security\User\DTO;

class SignUpSimpleDTO
{
    public ?string $login = null;
    public ?string $password = null;

    public bool $agreeToTerms = false;
}