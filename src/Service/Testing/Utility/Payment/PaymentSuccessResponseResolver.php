<?php

namespace Silecust\WebShop\Service\Testing\Utility\Payment;

use Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver\PaymentSuccessResponseResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sample class for Shipping charge, normally API would return these values
 */
class PaymentSuccessResponseResolver implements PaymentSuccessResponseResolverInterface
{
    public function resolve(Request $request): string|false
    {
        $paymentResponse = [
            "id" => "pay_G8VQzjPLoAvm6D",
            "entity" => "payment",
            "amount" => 1000,
            "currency" => "INR",
            "status" => "captured",
            "order_id" => "order_G8VPOayFxWEU28"

        ];

        // the api will call the URL with information baked in request
        return json_encode($paymentResponse);

    }
}