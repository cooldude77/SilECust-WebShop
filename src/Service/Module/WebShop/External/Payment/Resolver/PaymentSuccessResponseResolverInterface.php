<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface PaymentSuccessResponseResolverInterface
{

    public function resolve(Request $request);
}