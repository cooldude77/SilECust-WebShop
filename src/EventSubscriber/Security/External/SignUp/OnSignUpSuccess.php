<?php

namespace App\EventSubscriber\Security\External\SignUp;

use App\Event\Security\External\SignUp\SignUpEvent;
use App\Event\Security\SecurityEventTypes;
use App\Service\Common\Email\EmailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

readonly class OnSignUpSuccess implements EventSubscriberInterface
{
    public function __construct(
        private readonly EmailService $emailService,
       #[Autowire(param: 'silecust.sign_up.email.email_from_address')]
        private readonly string $fromEmail,
        #[Autowire(param: 'silecust.sign_up.email.headline')]
        private readonly string $headLine,
        #[Autowire(param: 'silecust.sign_up.email.template_location')]
        private readonly string $templateLocation
    ) {
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

        $email = (new TemplatedEmail())
            ->from($this->fromEmail)
            ->to($signUpEvent->getCustomer()->getEmail())
            ->subject($this->headLine)
            ->htmlTemplate($this->templateLocation)
            ->context(['customer'=>$signUpEvent->getCustomer()]);

        try {
            $this->emailService->send($email);
        } catch (TransportExceptionInterface $e) {

        }


    }
}