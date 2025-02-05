<?php

namespace App\Service\Component\Controller;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestStackHelper
{

    public function __construct(private RequestStack $requestStack)
    {
    }
}