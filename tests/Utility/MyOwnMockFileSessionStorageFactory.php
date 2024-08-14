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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;

// Help opcache.preload discover always-needed symbols
class_exists(MockFileSessionStorage::class);

/**
 * @author Ashvini
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * This is a copy of MockFileSessionStorageFactory
 * It keeps the storage session between calls in a variable
 */
class MyOwnMockFileSessionStorageFactory implements SessionStorageFactoryInterface
{
    private ?string $savePath;
    private string $name;
    private ?MetadataBag $metaBag;
    private ?MockFileSessionStorage $storage =null;

    /**
     * @see MockFileSessionStorage constructor.
     */
    public function __construct(?string $savePath = null, string $name = 'MOCKSESSID',
        ?MetadataBag $metaBag = null
    ) {
        $this->savePath = $savePath;
        $this->name = $name;
        $this->metaBag = $metaBag;
    }

    public function createStorage(?Request $request
    ): \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface {

        if ($this->storage == null) {
            $this->storage = new MockFileSessionStorage(
                $this->savePath, $this->name,
                $this->metaBag
            );
        }

        return $this->storage;
    }

    public function getStorage()
    {
        return $this->storage;
    }
}
