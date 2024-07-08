<?php

namespace App\Tests\Command\Security\User;

use App\Command\Security\User\CustomerCreateCommand;
use App\Tests\Fixtures\CustomerFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Console\Test\InteractsWithConsole;

class CustomerCreateCommandTest extends KernelTestCase
{

    use InteractsWithConsole, CustomerFixture;


    public function testCreateSampleCustomer()
    {
        $this->executeConsoleCommand('silecust:customer:sample:create', [
            $this->customerEmailInString,
            $this->firstNameInString,
            $this->lastNameInString,
            $this->passwordForCustomerInString
        ])
            ->assertSuccessful(); // command exit code is 0
        // todo: checkoutput
    }
}