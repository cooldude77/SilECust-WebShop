<?php

namespace App\Tests;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/TestKernel.php';

        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        // $this->handler = $container->get('my_own.handling.handler');
    }

    public function testHandle()
    {
        $a = 1;
        $this->assertEquals(1, $a);
    }
}