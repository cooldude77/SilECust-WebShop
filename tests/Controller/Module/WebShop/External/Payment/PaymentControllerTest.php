<?php /** @noinspection ALL */

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderJournal;
use Silecust\WebShop\Entity\OrderPayment;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Silecust\WebShop\Service\Testing\Fixtures\CartFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CurrencyFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderItemFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderShippingFixture;
use Silecust\WebShop\Service\Testing\Fixtures\PriceFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

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
        OrderShippingFixture,
        SessionFactoryFixture,
        Factories;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }


    public function testOnPaymentStart()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->product1, $this->product2, $this->currency);
        $this->createOrderFixturesA($this->customerA);
        $this->createOpenOrderItemsFixtureA($this->openOrderHeaderA, $this->product1, $this->product2);
        $this->createOrderShippingFixture($this->openOrderHeaderA);

        $uri = "/payment/order/{$this->openOrderHeaderA->getGeneratedId()}/start";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // start from here because shipping call is triggered here and shipping costs are added here
            ->visit('/checkout/order/view')
            ->assertSuccessful()
            ->visit($uri)
            ->assertSee(4930);
    }

    public function testOnPaymentSuccess()
    {
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
        $this->createOrderFixturesA($this->customerA);

        $uri = "/payment/order/{$this->openOrderHeaderA->getGeneratedId()}/success";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());

            })
            ->interceptRedirects()
            ->post($uri,
                [
                    'body' => [
                        OrderPayment::PAYMENT_GATEWAY_RESPONSE =>
                            [
                                'payment_id' => 'An id'
                            ]
                    ]
                ])
            ->assertRedirectedTo("/order/{$this->openOrderHeaderA->getGeneratedId()}/success", 1)
            ->use(callback: function (KernelBrowser $browser) {
                $this->createSession($browser);

                // check cart is emptied
                self::assertNull(
                    $this->session->get(CartProductManager::CART_SESSION_KEY)
                );

            });

        /** @var OrderHeader $header */
        $header = $this->findOneBy(
            OrderHeader::class, ['id' => $this->openOrderHeaderA->getId()]
        );
        self::assertEquals(
            OrderStatusTypes::ORDER_PAYMENT_COMPLETE,
            $header->getOrderStatusType()->getType()
        );

        $journal = $this->findOneBy(OrderJournal::class, ['orderHeader' => $this->openOrderHeaderA->object()]);

        $this->assertNotNull($journal);
    }

    public function testOnPaymentFailure()
    {
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
        $this->createOrderFixturesA($this->customerA);

        $uri = "/payment/order/{$this->openOrderHeaderA->getGeneratedId()}/failure";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());

            })
            ->post($uri,
                [
                    'body' => [
                        OrderPayment::PAYMENT_GATEWAY_RESPONSE =>
                            [
                                'payment_id' => 'An id'
                            ]
                    ]
                ]);


        /** @var OrderHeader $header */
        $header = $this->findOneBy(
            OrderHeader::class, ['id' => $this->openOrderHeaderA->object()]
        );
        self::assertEquals(
            OrderStatusTypes::ORDER_PAYMENT_FAILED,
            $header->getOrderStatusType()->getType()
        );

        $journal = $this->findOneBy(OrderJournal::class, ['orderHeader' => $this->openOrderHeaderA->object()]);

        $this->assertNotNull($journal);
    }
}