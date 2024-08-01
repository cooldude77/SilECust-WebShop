<?php

namespace App\Tests\Command\Security\User;

use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\EmployeeFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Console\Test\InteractsWithConsole;

class SuperUserCreateCommandTest extends KernelTestCase
{

    use InteractsWithConsole, EmployeeFixture;


    public function testCreateSuperUser()
    {
        $this->executeConsoleCommand('silecust:user:super:create', [
            $this->emailOfEmployeeInString,
            $this->firstNameOfEmployeeInString,
            $this->lastNameOfEmployeeInString,
            $this->passwordForEmployeeInString
        ])
            ->assertSuccessful(); // command exit code is 0
  // todo: checkoutput
    }
}
