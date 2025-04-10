<?php

namespace Silecust\WebShop\Tests\Controller\Security\External\Credentials\SignUp;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\User;
use Silecust\WebShop\Factory\CustomerFactory;
use Silecust\WebShop\Factory\UserFactory;
use Silecust\WebShop\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Mailer\Test\InteractsWithMailer;

class SignUpControllerTest extends WebTestCase
{
    use HasBrowser, FindByCriteria, InteractsWithMailer,Factories;

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
                'sign_up_simple_form[password]', 'random password'
            )
            ->fillField('sign_up_simple_form[agreeTerms]', true)
            ->interceptRedirects()
            ->click('Sign Up')
            ->assertAuthenticated()
            ->assertRedirectedTo('/')
            ->use(function () {
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
        $createUrl = '/signup/advanced?_redirect_after_success=/';


        $this->browser()->visit($createUrl)
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

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }
}
