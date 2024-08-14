<?php

namespace App\Tests\Controller\Security\External\Credentials;

use App\Entity\Customer;
use App\Entity\User;
use App\Factory\CustomerFactory;
use App\Factory\SalutationFactory;
use App\Factory\UserFactory;
use App\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Mailer\Test\InteractsWithMailer;

class SignUpControllerTest extends WebTestCase
{
    use HasBrowser, FindByCriteria, InteractsWithMailer;

    public function testSignUp()
    {
        $uri = '/signup';

        $this->browser()
            // use before visiting
            ->interceptRedirects()
            ->visit($uri)
            ->fillField(
                'sign_up_simple_form[login]', 'x@y.com'
            )->fillField(
                'sign_up_simple_form[password]', 'fwfwefwefwqwe2234fwf'
            )
            ->fillField('sign_up_simple_form[agreeTerms]', true)
            ->interceptRedirects()
            ->click('Sign Up')
            ->assertRedirectedTo('/')
            ->use(function (Browser $browser) {
                $user = $this->findOneBy(User::class, ['login' => 'x@y.com']);
                $customer = $this->findOneBy(Customer::class, ['email' => 'x@y.com']);

                $this->assertNotNull($user);
                $this->assertTrue(in_array('ROLE_CUSTOMER', $user->getRoles()));
                $this->assertNotNull($customer);
                $this->assertEquals('x@y.com', $customer->getEmail());


            });
        $this->mailer()->assertSentEmailCount(1);

    }

    /**
     *
     * Todo: // still need to find up valid use case for this
     *
     * @return void
     *
     */
    public function testSignUpAdvanced()
    {
        $createUrl = '/signup/advanced?_redirect_after_success=home';


        $salutation = SalutationFactory::createOne(['name' => 'Mr.',
                                                    'description' => 'Mister...']);

        $this->browser()->visit($createUrl)
            ->use(function (Browser $browser, Crawler $crawler) use ($salutation) {

                $domDocument = $crawler->getNode(0)?->parentNode;

                $option = $domDocument->createElement('option');
                $option->setAttribute('value', $salutation->getId());
                $selectElement = $crawler->filter('select')->getNode(0);
                $selectElement->appendChild($option);
            })
            ->fillField(
                'user_sign_up_advanced_form[firstName]', 'First Name'
            )->fillField(
                'user_sign_up_advanced_form[lastName]', 'Last Name'
            )->fillField('user_sign_up_advanced_form[email]', 'x@y.com')
            ->fillField('user_sign_up_advanced_form[phoneNumber]', '+91999999999')
            ->fillField('user_sign_up_advanced_form[plainPassword]', '4534geget355$%^')
            ->click('Save')
            ->assertSuccessful();

        $created = CustomerFactory::find(array('firstName' => 'First Name'));

        $this->assertEquals("First Name", $created->getFirstName());

        $created = UserFactory::find(array('login' => 'x@y.com'));
        $customer = CustomerFactory::find(['user' => $created]);

        $this->assertNotNull($created);
        $this->assertTrue(in_array('ROLE_CUSTOMER', $created->getRoles()));
        $this->assertNotNull($customer);


    }
}
