<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Utility;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;

// Help opcache.preload discover always-needed symbols
class_exists(Session::class);

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class MySessionFactory implements SessionFactoryInterface
{
    private RequestStack $requestStack;
    private SessionStorageFactoryInterface $storageFactory;
    private ?\Closure $usageReporter;

    static ?Session $session = null;

    public function __construct(RequestStack $requestStack,
        SessionStorageFactoryInterface $storageFactory, ?callable $usageReporter = null
    ) {
        $this->requestStack = $requestStack;
        $this->storageFactory = $storageFactory;
        $this->usageReporter = null === $usageReporter ? null : $usageReporter(...);
    }

    public function createSession(): \Symfony\Component\HttpFoundation\Session\SessionInterface
    {
        if (self::$session == null) {
            self::$session = new Session(
                $this->storageFactory->createStorage($this->requestStack->getMainRequest()), null,
                null, $this->usageReporter
            );
        }

        return self::$session;

    }
}
