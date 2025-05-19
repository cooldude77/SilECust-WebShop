<?php

namespace Silecust\WebShop\Tests\Controller\Security\External\Credentials;

use Doctrine\ORM\EntityManagerInterface;
use Silecust\WebShop\Factory\UserFactory;
use Silecust\WebShop\Repository\UserRepository;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Mailer\Test\InteractsWithMailer;
use Zenstruck\Mailer\Test\TestEmail;

class ResetPasswordControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    use HasBrowser, CustomerFixture, InteractsWithMailer, Factories;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
    }

    public function testResetPasswordController(): void
    {

        $this->createCustomerFixtures();

        // Test Request reset password page
        $this->browser()
            ->visit('/reset-password')
            ->use(function () {
                self::assertResponseIsSuccessful();
                self::assertPageTitleContains('Reset your password');
            })
            // Submit the reset password form and test email message is queued / sent
            ->fillField('reset_password_request_form[login]', $this->customer->getUser()->getLogin())
            ->click('Send password reset email')
            ->assertSee('Password Reset Email Sent')
            ->assertSee('This link will expire in 1 hour');

        $link = '';
        $this->mailer()
            ->assertSentEmailCount(1)
            ->assertEmailSentTo($this->userForCustomer->getLogin(), function (TestEmail $email) use (&$link) {
                $email
                    ->assertSubject('Your password reset request')
                    ->assertFrom(self::$kernel->getContainer()->getParameter('silecust.sign_up.email.email_from_address'))
                    ->assertTextContains('This link will expire in 1 hour.');
                $text = $email->getHtmlBody();
                preg_match('#(/reset-password/reset/[a-zA-Z0-9]+)#', $text, $resetLink);
                $link = $resetLink[0];

            });

        $this->browser()
            ->interceptRedirects()
            ->visit($link)
            ->followRedirects()
            ->interceptRedirects()
            ->fillField('change_password_form[plainPassword][first]', 'newStrongPassword')
            ->fillField('change_password_form[plainPassword][second]', 'newStrongPassword')
            ->click('Reset password')
            ->assertRedirectedTo('/');

        $passwordHasher = static::getContainer()->get('security.user_password_hasher');

        $user = UserFactory::find($this->customer->getUser());

        self::assertTrue($passwordHasher->isPasswordValid($user->object(), 'newStrongPassword'));


    }
}
