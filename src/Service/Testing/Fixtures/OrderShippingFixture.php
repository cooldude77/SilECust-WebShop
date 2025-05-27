<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Factory\OrderShippingFactory;
use Zenstruck\Foundry\Proxy;

trait OrderShippingFixture
{

    private Proxy|OrderShipping $orderShipping;


    public function createOrderShippingFixture( Proxy|OrderHeader  $orderHeader): void
    {
        $this->orderShipping = OrderShippingFactory::createOne(['orderHeader' => $orderHeader,
            'value'=> 100]);
    }


}