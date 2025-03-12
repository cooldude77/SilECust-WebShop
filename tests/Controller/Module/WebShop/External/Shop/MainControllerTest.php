<?php

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Shop;

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
            ->assertSuccessful();

        //    ->assertSeeElement('a#logo-home-link');

    }
}
