<?php

namespace App\Tests\Controller\Module\WebShop\External\Payment;

use App\Entity\OrderHeader;
use App\Entity\OrderItemPaymentPrice;
use App\Service\Transaction\Order\Status\OrderStatusTypes;
use App\Tests\Fixtures\CartFixture;
use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\OrderItemFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use App\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;

class PaymentControllerTest extends WebTestCase
{
    use HasBrowser,
        CurrencyFixture,
        CustomerFixture,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        FindByCriteria,
        CartFixture,
        OrderFixture,
        OrderItemFixture,
        SessionFactoryFixture;


    public function testOnPaymentStart()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOpenOrderFixtures($this->customer);
        $this->createOrderItemsFixture($this->orderHeader, $this->productA, $this->productB);

        $uri = "/checkout/payment/start";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            ->visit($uri)
            ->assertSee(4830);
    }

    public function testOnPaymentSuccess()
    {
        $this->createCustomerFixtures();
        $this->createLocationFixtures();

        $uri = "/checkout/payment/success";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrderFixtures($this->customer);

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
        $this->createCustomerFixtures();
        $this->createLocationFixtures();

        $uri = "/checkout/payment/failure";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrderFixtures($this->customer);

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