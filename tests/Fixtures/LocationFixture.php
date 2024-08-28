<?php

namespace App\Tests\Fixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\PostalCode;
use App\Entity\State;
use App\Factory\CityFactory;
use App\Factory\CountryFactory;
use App\Factory\PostalCodeFactory;
use App\Factory\StateFactory;
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