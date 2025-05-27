<?php

namespace Silecust\WebShop\Tests\Service\Payment;

use Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver\PaymentFailureResponseResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sample class for Shipping charge, normally API would return these values
 */
class PaymentFailureResponseResolver implements PaymentFailureResponseResolverInterface
{
    public function resolve(Request $request): string
    {
        $paymentResponse = [
            "id" => "pay_G8VQzjPLoAvm6D",
            "entity" => "payment",
            "amount" => 1000,
            "currency" => "INR",
            "status" => "failure",
            "order_id" => "order_G8VPOayFxWEU28"

        ];

        // the api will call the URL with information baked in request
        return json_encode($paymentResponse);

    }
}