<?php

namespace App\Tests\Controller\Module\WebShop\External\Shop;

use App\Controller\Module\WebShop\External\Shop\MainController;
use App\Entity\WebShop;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MainControllerTest extends WebTestCase
{

    use HasBrowser;
    public function testShop()
    {

        // visit home , not logged in
        $this->browser()
            ->visit('/')
            ->assertSeeElement('a#logo-home-link');

    }
}
