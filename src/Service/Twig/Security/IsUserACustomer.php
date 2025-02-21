<?php

namespace Silecust\WebShop\Service\Twig\Security;

use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IsUserACustomer extends AbstractExtension
{
    public function __construct(private readonly CustomerFromUserFinder $customerFromUserFinder)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isLoggedInUserAlsoACustomer', [$this, 'isLoggedInUserAlsoACustomer']),
        ];
    }

    public function isLoggedInUserAlsoACustomer(): bool
    {
        return $this->customerFromUserFinder->isLoggedInUserAlsoACustomer();
    }
}