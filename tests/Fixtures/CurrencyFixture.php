<?php

namespace Silecust\WebShop\Tests\Fixtures;

use Silecust\WebShop\Entity\City;
use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Entity\PostalCode;
use Silecust\WebShop\Entity\State;
use Silecust\WebShop\Factory\CityFactory;
use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Factory\CurrencyFactory;
use Silecust\WebShop\Factory\PostalCodeFactory;
use Silecust\WebShop\Factory\StateFactory;
use Zenstruck\Foundry\Proxy;

trait CurrencyFixture
{
    public Currency|Proxy $currency;

    function createCurrencyFixtures(Proxy|Country $country): void
    {

        $this->currency = CurrencyFactory::createOne(['country'=>$country,
                                                      'code' => 'INR',
                                                      'description' => 'Indian Rupees',
                                                      'symbol' => '₹',
        ]);


    }
}