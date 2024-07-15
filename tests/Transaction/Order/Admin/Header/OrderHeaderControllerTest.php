<?php

namespace App\Tests\Transaction\Order\Admin\Header;

use App\Entity\OrderHeader;
use App\Factory\OrderFactory;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class OrderHeaderControllerTest extends WebTestCase
{

    use HasBrowser, FindByCriteria, CustomerFixture;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createCustomer();

        $createUrl = '/order/create';

        $visit = $this->browser()->visit($createUrl)->use(function (Browser $browser) {
            $crawler = $browser->crawler();

            $domDocument = $crawler->getNode(0)?->parentNode;

            $option = $domDocument->createElement('option');
            $option->setAttribute('value', $this->customer->getId());
            $selectElement = $crawler->filter('select')->getNode(0);
            $selectElement->appendChild($option);

        })->fillField('order_header_create_form[customer]', $this->customer->getId())
            ->click('Choose')
            ->assertSuccessful();

        $created = $this->findOneBy(OrderHeader::class, ['customer' => $this->customer->object()]);


    }

}
