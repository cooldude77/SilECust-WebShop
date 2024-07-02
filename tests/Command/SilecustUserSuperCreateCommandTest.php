<?php

namespace App\Tests\Command;

use App\Tests\Fixtures\CustomerFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Console\Test\InteractsWithConsole;

class SilecustUserSuperCreateCommandTest extends KernelTestCase
{

    use InteractsWithConsole, CustomerFixture;


    public function testCreateSuperUser()
    {
        $this->executeConsoleCommand('silecust:user:super:create', [
            $this->customerEmailInString,
            $this->firstNameInString,
            $this->lastNameInString,
            $this->passwordForCustomerInString
        ])
            ->assertSuccessful(); // command exit code is 0
  // todo: checkoutput
    }
}
