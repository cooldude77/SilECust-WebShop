<?php

namespace Silecust\WebShop\Service\Testing\Utility;

use Silecust\WebShop\Factory\EmployeeFactory;
use Zenstruck\Foundry\Proxy;

trait AuthenticateTestEmployee
{
    private function authenticateEmployee(\Symfony\Bundle\FrameworkBundle\KernelBrowser $client
    ): Proxy {   // Authenticated entry
        $employee = EmployeeFactory::createOne();

        $client->loginUser($employee->getUser());

        return $employee;
    }

}