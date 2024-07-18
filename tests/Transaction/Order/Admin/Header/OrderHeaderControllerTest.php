<?php

namespace App\Tests\Transaction\Order\Admin\Header;

use App\Entity\OrderHeader;
use App\Entity\OrderStatusType;
use App\Factory\OrderStatusTypeFactory;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Utility\FindByCriteria;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class OrderHeaderControllerTest extends WebTestCase
{

    use HasBrowser, FindByCriteria, CustomerFixture, SelectElement,OrderFixture;

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createCustomerFixtures();

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

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $this->createCustomerFixtures();
        $this->createOpenOrderFixtures($this->customer);

        $createUrl = "/order/{$this->orderHeader->getId()}/edit";

        /** @var OrderStatusType $statusType */
        $statusType = OrderStatusTypeFactory::find(['type'=>'ORDER_SHIPPED']);

        $this->browser()->visit($createUrl)->use(function (Browser $browser) use($statusType) {
            $this->addOption(
                $browser, 'select[name="order_header_edit_form[orderStatusType]"]',
                $statusType->getId()
            );

        })->fillField('order_header_edit_form[orderStatusType]', $statusType->getId())
            ->click('Choose')
            ->assertSuccessful();

        $created = $this->findOneBy(OrderHeader::class, ['customer' => $this->customer->object()]);

        self::assertNotNull($created);

    }


    public function testList()
    {
        $this->createCustomerFixtures();
        $this->createOpenOrderFixtures($this->customer);

        $url = "order/list";
        $this->browser()->visit($url)->assertSuccessful();

    }

}
