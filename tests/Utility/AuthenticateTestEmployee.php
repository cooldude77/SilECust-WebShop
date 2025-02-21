<?php

namespace Silecust\WebShop\Tests\Utility;

use Silecust\WebShop\Entity\Employee;
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