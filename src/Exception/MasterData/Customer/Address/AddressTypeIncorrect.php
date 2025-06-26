<?php

namespace Silecust\WebShop\Exception\MasterData\Customer\Address;

class AddressTypeIncorrect extends \Exception
{

    public function __construct(string $addressType)
    {
        parent::__construct("{$addressType} is not a correct address type");
    }
}