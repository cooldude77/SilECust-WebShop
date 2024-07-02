<?php

namespace App\Tests\Controller\Module\WebShop\External\Address;

use App\Controller\Component\Routing\RoutingConstants;
use App\Entity\CustomerAddress;
use App\Entity\OrderAddress;
use App\Factory\CustomerAddressFactory;
use App\Service\Module\WebShop\External\Address\CheckOutAddressSession;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use App\Tests\Utility\FindByCriteria;
use App\Tests\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Proxy;

class AddressControllerTest extends WebTestCase
{
    use HasBrowser, CustomerFixture, LocationFixture, SelectElement, SessionFactoryFixture,
        FindByCriteria, OrderFixture;


    private Proxy|CustomerAddress $shippingAddress;
    private Proxy|CustomerAddress $billingAddress;

    public function testCreateAddressesWhenNoAddressesPresent()
    {
        $this->createCustomer();
        $this->createLocationFixtures();


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

                $this->session->set(
                    CheckOutAddressSession::SHIPPING_ADDRESS_ID, $this->shippingAddress->getId()
                );

            })->interceptRedirects()->visit($uri)->assertRedirectedTo(
                '/checkout/addresses/choose?type=billing&_redirect_upon_success_url=/checkout/addresses',
                1
            )->use(callback: function (KernelBrowser $browser) {

                $this->session->set(
                    CheckOutAddressSession::BILLING_ADDRESS_ID, $this->billingAddress->getId()
                );

            })->interceptRedirects()->visit($uri)->assertRedirectedTo('/checkout', 1);


    }


    public function testCreateAddressShipping()
    {
        $this->createCustomer();
        $this->createLocationFixtures();

        $uri = "/checkout/address/create?type=shipping&"
            . RoutingConstants::REDIRECT_UPON_SUCCESS_URL . '=/checkout/addresses';
        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());

                $this->createOpenOrder($this->customer);

            })
            ->interceptRedirects()
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->pinCode->getId());
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
                'address_create_and_choose_form[address][pinCode]', $this->pinCode->getId()
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
        $this->createCustomer();
        $this->createLocationFixtures();

        $uri = "/checkout/address/create?type=billing&"
            . RoutingConstants::REDIRECT_UPON_SUCCESS_URL . '=/checkout/addresses';

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
                $this->createOpenOrder($this->customer);

            })
            ->interceptRedirects()
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption($browser, 'select', $this->pinCode->getId());
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
                'address_create_and_choose_form[address][pinCode]', $this->pinCode->getId()
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
        $this->createCustomer();
        $this->createLocationFixtures();


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
                $this->createOpenOrder($this->customer);
            })
            ->interceptRedirects()
            ->visit($uriShipping)
            ->checkField(
                "address_choose_existing_multiple_form[addresses][0][isChosen]"
            )
            ->click('Choose')
            ->assertRedirectedTo('/checkout/addresses', 1)
            ->use(
                function (KernelBrowser $browser) use ($address1Shipping,$address1Billing) {
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
                $this->createOpenOrder($this->customer);
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