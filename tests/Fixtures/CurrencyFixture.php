<?php

namespace App\Tests\Fixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\PinCode;
use App\Entity\State;
use App\Factory\CityFactory;
use App\Factory\CountryFactory;
use App\Factory\CurrencyFactory;
use App\Factory\PinCodeFactory;
use App\Factory\StateFactory;
use Zenstruck\Foundry\Proxy;

trait CurrencyFixture
{
    public Currency|Proxy $currency;

    function createCurrencyFixtures(): void
    {

        $this->currency = CurrencyFactory::createOne();


    }
}