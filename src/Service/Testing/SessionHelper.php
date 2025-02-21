<?php

namespace Silecust\WebShop\Service\Testing;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

trait SessionHelper
{
    // Solution provided in
    // https://github.com/symfony/symfony/discussions/45662
    // set session.save_key in test environment in framework.yaml
    /*
     when@test:
    framework:
        test: true
        session:
            storage_factory_id: session.storage.factory.mock_file
            save_path: '%kernel.cache_dir%/sessions'
     */
    /**
     * @param KernelBrowser $client
     *
     * @return Session
     */
    public function createSession(KernelBrowser $client): Session
    {
        $container = $client->getContainer();
        $sessionSavePath = $container->getParameter('session.save_path');
        $sessionStorage = new MockFileSessionStorage($sessionSavePath);

        $session = new Session($sessionStorage);
        $session->start();
        $session->save();

        $sessionCookie = new Cookie(
            $session->getName(),
            $session->getId(),
            null,
            null,
            'localhost',
        );
        $client->getCookieJar()->set($sessionCookie);

        return $session;
    }

    public function generateCsrfToken(Session $session, TokenGeneratorInterface $tokenGenerator,
        string $tokenId
    ): string {

        $csrfToken = $tokenGenerator->generateToken();
        $session->set(SessionTokenStorage::SESSION_NAMESPACE . "/{$tokenId}", $csrfToken);
        $session->save();
        return $csrfToken;

    }
}