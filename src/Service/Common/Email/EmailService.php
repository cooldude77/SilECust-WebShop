<?php

namespace App\Service\Common\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 *
 */
class EmailService
{

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /**
     * @param TemplatedEmail $email
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function send(TemplatedEmail $email): void
    {
        $this->mailer->send($email);
    }
}