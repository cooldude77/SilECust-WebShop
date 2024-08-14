<?php

namespace App\Form\Module\WebShop\External\Order\DTO\Components;

class OrderAddressDTO
{

    public ?int $id = null;

    public ?string $line1 = null;

    public ?string $line2 = null;

    public ?string $line3 = null;

    public ?string $pinCode = null;

    public ?string $city = null;

    public ?string $state = null;

    public ?string $country = null;

    public ?string $addressType = null;
}