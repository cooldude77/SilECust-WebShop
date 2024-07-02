<?php

namespace App\Tests\Controller\Module\WebShop\External\Product;

use App\Factory\ProductFactory;
use App\Service\Testing\SessionHelper;
use App\Tests\Fixtures\CustomerFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class ProductControllerTest extends WebTestCase
{
    use HasBrowser;
    use SessionHelper,CustomerFixture;




}
