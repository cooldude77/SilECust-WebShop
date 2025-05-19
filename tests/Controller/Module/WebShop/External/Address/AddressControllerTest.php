<?php

namespace Silecust\WebShop\Tests\Controller\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Entity\OrderAddress;
use Silecust\WebShop\Factory\CustomerAddressFactory;
use Silecust\WebShop\Service\Component\Routing\RoutingConstants;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressSession;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Fixtures\OrderFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SessionFactoryFixture;
use Silecust\WebShop\Service\Testing\Utility\FindByCriteria;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Test\Factories;

class AddressControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture, LocationFixture, SelectElement, SessionFactoryFixture,
        FindByCriteria, OrderFixture, Factories;


    private Proxy|CustomerAddress $shippingAddress;
    private Proxy|CustomerAddress $billingAddress;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createCustomerFixtures();
        $this->createLocationFixtures();

    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

    public function testCreateAddressesWhenNoAddressesPresent()
    {

        $uri = "/checkout/addresses?" . RoutingConstants::REDIRECT_UPON_SUCCESS_URL . '=/checkout';
        $this->browser()->use(callback: function (Browser $browser) {
            $browser->client()->loginUser($this->userForCustomer->object());
        })
            // address exists
            ->interceptRedirects()->visit($uri)->assertRedirectedTo(
                '/checkout/address/create?type=shipping&_redirect_upon_success_url=/checkout/addresses',
                1
            )->use(callback: function (Browser $browser) {

                // assume address is created
                $this->shippingAddress = CustomerAddressFactory::createOne(
                    ['customer' => $this->customer,
                        'addressType' => 'shipping',
                        'line1' => 'A Good House']
                );

            })->interceptRedirects()->visit($uri)->assertRedirectedTo(
                '/checkout/address/create?type=billing&_redirect_upon_success_url=/checkout/addresses',
                1
            )->use(callback: function (Browser $browser) {

                // assume address is created
                $this->billingAddress = CustomerAddressFactory::createOne(
                    ['customer' => $this->customer,
                        'addressType' => 'billing',
                        'line1' => 'A Good House']
                );
            })->interceptRedirects()->visit($uri)->assertRedirectedTo(
                '/checkout/addresses/choose?type=shipping&_redirect_upon_success_url=/checkout/addresses',
                1
            )->use(callback: function (KernelBrowser $browser) {
                $this->createSession($browser);

                $this->saveToSession(
                    CheckOutAddressSession::SHIPPING_ADDRESS_ID, $this->shippingAddress->getId()
                );


            })->interceptRedirects()->visit($uri)->assertRedirectedTo(
                '/checkout/addresses/choose?type=billing&_redirect_upon_success_url=/checkout/addresses',
                1
            )->use(callback: function (KernelBrowser $browser) {

                $this->saveToSession(
                    CheckOutAddressSession::BILLING_ADDRESS_ID, $this->billingAddress->getId()
                );

            })->interceptRedirects()->visit($uri)->assertRedirectedTo('/checkout', 1);


    }


    public function testCreateAddressShipping()
    {
        $uri = "/checkout/address/create?type=shipping&"
            . RoutingConstants::REDIRECT_UPON_SUCCESS_URL . '=/checkout/addresses';
        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());

                $this->createOpenOrderFixtures($this->customer);

            })
            ->interceptRedirects()
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField(
                'address_create_and_choose_form[address][line1]', 'Line 1'
            )
            ->fillField(
                'address_create_and_choose_form[address][line2]', 'Line 2'
            )
            ->fillField(
                'address_create_and_choose_form[address][line3]', 'Line 3'
            )
            ->fillField(
                'address_create_and_choose_form[address][postalCode]', $this->postalCode->getId()
            )
            ->fillField(
                'address_create_and_choose_form[address][addressType]', 'shipping'
            )
            ->checkField(
                'address_create_and_choose_form[address][isDefault]'
            )
            ->checkField('address_create_and_choose_form[isChosen]')
            ->click('Save')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(function (KernelBrowser $browser) {
                $this->createSession($browser);
                self::assertNotNull(
                    $this->session->get(CheckOutAddressSession::SHIPPING_ADDRESS_ID)
                );

                $address = $this->findOneBy(
                    CustomerAddress::class,
                    ['customer' => $this->customer->object()]
                );

                $orderAddress = $this->findOneBy(
                    OrderAddress::class, ['shippingAddress' => $address]
                );

                self::assertNotNull($orderAddress);

            });
    }

    public function testCreateAddressBilling()
    {

        $uri = "/checkout/address/create?type=billing&"
            . RoutingConstants::REDIRECT_UPON_SUCCESS_URL . '=/checkout/addresses';

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrderFixtures($this->customer);

            })
            ->interceptRedirects()
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->postalCode->getId());
            })
            ->fillField(
                'address_create_and_choose_form[address][line1]', 'Line 1'
            )
            ->fillField(
                'address_create_and_choose_form[address][line2]', 'Line 2'
            )
            ->fillField(
                'address_create_and_choose_form[address][line3]', 'Line 3'
            )
            ->fillField(
                'address_create_and_choose_form[address][postalCode]', $this->postalCode->getId()
            )
            ->fillField(
                'address_create_and_choose_form[address][addressType]', 'billing'
            )
            ->checkField(
                'address_create_and_choose_form[address][isDefault]'
            )
            ->checkField('address_create_and_choose_form[isChosen]')
            ->click('Save')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(function (KernelBrowser $browser) {
                $this->createSession($browser);
                self::assertNotNull(
                    $this->session->get(CheckOutAddressSession::BILLING_ADDRESS_ID)
                );

                $address = $this->findOneBy(
                    CustomerAddress::class,
                    ['customer' => $this->customer->object()]
                );

                $orderAddress = $this->findOneBy(
                    OrderAddress::class, ['billingAddress' => $address]
                );

                self::assertNotNull($orderAddress);

            });
        //todo: check redirect
    }


    public function testChooseAddressesFromMultipleShippingAddresses()
    {


        // one address is created already

        $address1Shipping = CustomerAddressFactory::createOne(
            ['customer' => $this->customer, 'addressType' => 'shipping', 'line1' => 'Shipping 1']
        );
        $address2Shipping = CustomerAddressFactory::createOne(
            ['customer' => $this->customer, 'addressType' => 'shipping', 'line1' => 'Shipping 2']
        );
        $address1Billing = CustomerAddressFactory::createOne(
            ['customer' => $this->customer, 'addressType' => 'billing', 'line1' => 'billing 2']
        );
        $address2Billing = CustomerAddressFactory::createOne(
            ['customer' => $this->customer, 'addressType' => 'billing', 'line1' => 'billing 2']
        );


        $uriShipping = "/checkout/addresses/choose?type=shipping";
        $uriBilling = "/checkout/addresses/choose?type=billing";

        // first choose shipping
        $this
            ->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrderFixtures($this->customer);
            })
            ->interceptRedirects()
            ->visit($uriShipping)
            ->checkField(
                "address_choose_existing_multiple_form[addresses][0][isChosen]"
            )
            ->click('Choose')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(
                function (KernelBrowser $browser) use ($address1Shipping, $address1Billing) {
                    $this->createSession($browser);
                    self::assertNotNull(
                        $this->session->get(CheckOutAddressSession::SHIPPING_ADDRESS_ID)
                    );

                    self::assertEquals(
                        $this->session->get(CheckOutAddressSession::SHIPPING_ADDRESS_ID),
                        $address1Shipping->getId()
                    );

                    $orderAddress = $this->findOneBy(
                        OrderAddress::class, ['shippingAddress' => $address1Shipping->object()]
                    );

                    self::assertNotNull($orderAddress);

                }
            )
            // then choose billing
            ->interceptRedirects()
            ->visit($uriBilling)
            ->checkField(
                "address_choose_existing_multiple_form[addresses][0][isChosen]"
            )
            ->click('Choose')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(
                function (KernelBrowser $browser) use ($address1Billing) {
                    $this->createSession($browser);
                    self::assertNotNull(
                        $this->session->get(CheckOutAddressSession::BILLING_ADDRESS_ID)
                    );

                    self::assertEquals(
                        $this->session->get(CheckOutAddressSession::BILLING_ADDRESS_ID),
                        $address1Billing->getId()
                    );


                    $orderAddress = $this->findOneBy(
                        OrderAddress::class, ['billingAddress' => $address1Billing->object()]
                    );

                    self::assertNotNull($orderAddress);
                }
            );

        // choose different shipping
        $this
            ->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrderFixtures($this->customer);
            })
            ->interceptRedirects()
            ->visit($uriShipping)
            ->checkField(
                "address_choose_existing_multiple_form[addresses][1][isChosen]"
            )
            ->click('Choose')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(
                function (KernelBrowser $browser) use ($address2Shipping) {
                    $this->createSession($browser);
                    self::assertNotNull(
                        $this->session->get(CheckOutAddressSession::SHIPPING_ADDRESS_ID)
                    );

                    self::assertEquals(
                        $this->session->get(CheckOutAddressSession::SHIPPING_ADDRESS_ID),
                        $address2Shipping->getId()
                    );

                    $orderAddress = $this->findOneBy(
                        OrderAddress::class, ['shippingAddress' => $address2Shipping->object()]
                    );

                    self::assertNotNull($orderAddress);

                }
            )
            // then choose different billing
            ->interceptRedirects()
            ->visit($uriBilling)
            ->checkField(
                "address_choose_existing_multiple_form[addresses][1][isChosen]"
            )
            ->click('Choose')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(
                function (KernelBrowser $browser) use ($address2Billing) {
                    $this->createSession($browser);
                    self::assertNotNull(
                        $this->session->get(CheckOutAddressSession::BILLING_ADDRESS_ID)
                    );

                    self::assertEquals(
                        $this->session->get(CheckOutAddressSession::BILLING_ADDRESS_ID),
                        $address2Billing->getId()
                    );


                    $orderAddress = $this->findOneBy(
                        OrderAddress::class, ['billingAddress' => $address2Billing->object()]
                    );

                    self::assertNotNull($orderAddress);
                }
            );


    }
}