<?php

namespace Silecust\WebShop\Tests\Fixtures;

use Silecust\WebShop\Entity\City;
use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\PostalCode;
use Silecust\WebShop\Entity\State;
use Silecust\WebShop\Factory\CityFactory;
use Silecust\WebShop\Factory\CountryFactory;
use Silecust\WebShop\Factory\PostalCodeFactory;
use Silecust\WebShop\Factory\StateFactory;
use Zenstruck\Foundry\Proxy;

trait LocationFixture
{
    public Country|Proxy $country;
    public State|Proxy $state;
    public City|Proxy $city;
    public PostalCode|Proxy $postalCode;

    function createLocationFixtures(): void
    {

        $this->country = CountryFactory::createOne(['code'=>'IN','name'=>'India']);

        $this->state = StateFactory::createOne(['country' => $this->country,
            'code'=>'KA','name'=>'Karnataka']);
        $this->city = CityFactory::createOne(['state' => $this->state,
            'code'=>'BLR','name'=>'Bangalore']);
        $this->postalCode = PostalCodeFactory::createOne(['city' => $this->city,'postalCode'=>'560001','name'=>'Bangalore PO']);

    }
}