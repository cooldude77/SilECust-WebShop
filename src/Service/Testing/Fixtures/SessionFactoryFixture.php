<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Service\Testing\Utility\MySessionFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 */
trait SessionFactoryFixture
{

    /**
     * @var Session
     *             Session Factory will return that session from the time user has been logged in
     *             The old app session keys may exist and need to be reset in their own function calls
     *             before any test should be called
     *
     */
    private Session $session;

    /**
     * @param KernelBrowser $kernelBrowser
     *
     * @return void
     */
    public function createSession(KernelBrowser $kernelBrowser): void
    {

        /** @var MySessionFactory $factory */
        $factory = $kernelBrowser->getContainer()->get('session.factory');

        $this->session = $kernelBrowser->getRequest()->getSession() != null ? $kernelBrowser->getRequest()->getSession() : $factory->createSession();
    }

    public function saveToSession($key, $value): void
    {
        $this->session->set($key, $value);
        $this->session->save();
    }
}