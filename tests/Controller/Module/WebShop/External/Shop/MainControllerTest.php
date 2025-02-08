<?php

namespace App\Tests\Controller\Module\WebShop\External\Shop;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MainControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        require_once '/var/www/html/project/productized/bundles/silecust/tests/Kernel.php';

        $kernel = new Kernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        // $this->handler = $container->get('my_own.handling.handler');
    }

    use HasBrowser;

    public function testShop()
    {

        // visit home , not logged in
        $this->browser()
            ->visit('/')
            ->assertSeeElement('a#logo-home-link');

    }
}
