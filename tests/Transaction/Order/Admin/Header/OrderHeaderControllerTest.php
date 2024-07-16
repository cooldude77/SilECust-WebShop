<?php

namespace App\Tests\Transaction\Order\Admin\Header;

use App\Entity\OrderHeader;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Utility\FindByCriteria;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class OrderHeaderControllerTest extends WebTestCase
{

    use HasBrowser, FindByCriteria, CustomerFixture, SelectElement;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createCustomer();

        $createUrl = '/order/create';

        $this->browser()->visit($createUrl)->use(function (Browser $browser) {
            $this->addOption(
                $browser, 'select[name="order_header_create_form[customer]"]',
                $this->customer->getId()
            );

        })->fillField('order_header_create_form[customer]', $this->customer->getId())
            ->click('Choose')
            ->assertSuccessful();

        $created = $this->findOneBy(OrderHeader::class, ['customer' => $this->customer->object()]);

        self::assertNotNull($created);

    }

}
