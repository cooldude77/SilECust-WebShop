<?php

namespace App\Form\Module\WebShop\Admin\Order\DTO;

class OrderAddressDTO
{
    public int $id = 0;
    public int $orderId = 0;

    public ?string $line1 = null;
    public ?string $line2 = null;
    public ?string $line3 = null;
    public ?string $pinCode = null;

    public ?string $city = null;

    public ?string $state = null;

    public ?string $country= null;

    public bool $isShipping=false;
    public bool $isBilling = false;
}