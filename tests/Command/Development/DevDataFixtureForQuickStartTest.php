<?php

namespace App\Tests\Command\Development;

use App\Tests\Fixtures\CustomerFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Console\Test\InteractsWithConsole;

class DevDataFixtureForQuickStartTest extends KernelTestCase
{

    use InteractsWithConsole, CustomerFixture;

    protected function setUp(): void
    {

        static::bootKernel();
    }

    public function testCreateSampleCustomer()
    {
        $this->executeConsoleCommand('silecust:dev:data-fixture:create')
            ->assertSuccessful(); // command exit code is 0

    }
}