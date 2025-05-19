<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Factory\CurrencyFactory;
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