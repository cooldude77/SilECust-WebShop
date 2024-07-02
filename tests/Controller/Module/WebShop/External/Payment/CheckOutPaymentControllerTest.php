<?php

namespace App\Tests\Controller\Module\WebShop\External\Payment;

use App\Entity\OrderHeader;
use App\Service\Module\WebShop\External\Order\Status\OrderStatusTypes;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use App\Tests\Utility\FindByCriteria;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class CheckOutPaymentControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture, LocationFixture, SelectElement, SessionFactoryFixture,
        FindByCriteria, OrderFixture;

    public function testOnPaymentSuccess()
    {
        $this->createCustomer();
        $this->createLocationFixtures();

        $uri = "/checkout/payment/success";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrder($this->customer);

            })
            ->interceptRedirects()
            ->visit($uri)
            ->use(callback: function (Browser $browser) {

                /** @var OrderHeader $header */
                $header = $this->findOneBy(
                    OrderHeader::class, ['id' => $this->orderHeader->object()]
                );
                self::assertEquals(
                    OrderStatusTypes::ORDER_PAYMENT_COMPLETE,
                    $header->getOrderStatusType()->getType()
                );


            })
            ->assertRedirectedTo("/order/{$this->orderHeader->getId()}/success");
    }

    public function testOnPaymentFailure()
    {
        $this->createCustomer();
        $this->createLocationFixtures();

        $uri = "/checkout/payment/failure";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrder($this->customer);

            })
            ->visit($uri)
            ->use(callback: function (Browser $browser) {

                /** @var OrderHeader $header */
                $header = $this->findOneBy(
                    OrderHeader::class, ['id' => $this->orderHeader->object()]
                );
                self::assertEquals(
                    OrderStatusTypes::ORDER_PAYMENT_FAILED,
                    $header->getOrderStatusType()->getType()
                );


            });
    }
}
