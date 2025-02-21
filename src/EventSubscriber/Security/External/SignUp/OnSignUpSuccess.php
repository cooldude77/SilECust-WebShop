<?php

namespace Silecust\WebShop\EventSubscriber\Security\External\SignUp;

use Silecust\WebShop\Event\Security\External\SignUp\SignUpEvent;
use Silecust\WebShop\Event\Security\SecurityEventTypes;
use Silecust\WebShop\Service\Common\Email\EmailService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnSignUpSuccess implements EventSubscriberInterface
{
    public function __construct(
        private readonly EmailService    $emailService,
        #[Autowire(param: 'silecust.sign_up.email.email_from_address')]
        private readonly string          $fromEmail,
        #[Autowire(param: 'silecust.sign_up.email.headline')]
        private readonly string          $headLine,
        #[Autowire(param: 'silecust.sign_up.email.template_location')]
        private readonly string          $templateLocation,
        private readonly LoggerInterface $logger
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEventTypes::POST_CUSTOMER_SIGN_UP_SUCCESS => 'onCustomerSignUp'
        ];

    }

    /**
     * @param SignUpEvent $signUpEvent
     *
     * @return void
     */
    public function onCustomerSignUp(SignUpEvent $signUpEvent): void
    {

        try {
            $email = (new TemplatedEmail())
                ->from($this->fromEmail)
                ->to($signUpEvent->getCustomer()->getEmail())
                ->subject($this->headLine)
                ->htmlTemplate($this->templateLocation)
                ->context(['customer' => $signUpEvent->getCustomer()]);

            $this->emailService->send($email);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }


    }
}