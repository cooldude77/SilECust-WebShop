<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface PaymentFailureResponseResolverInterface
{
    public function resolve(Request $request);
}