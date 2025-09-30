<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\File;
use Silecust\WebShop\Repository\FileRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<File>
 *
 * @method        File|Proxy                              create(array|callable $attributes = [])
 * @method static File|Proxy                              createOne(array $attributes = [])
 * @method static File|Proxy                              find(object|array|mixed $criteria)
 * @method static File|Proxy                              findOrCreate(array $attributes)
 * @method static File|Proxy                              first(string $sortedField = 'id')
 * @method static File|Proxy                              last(string $sortedField = 'id')
 * @method static File|Proxy                              random(array $attributes = [])
 * @method static File|Proxy                              randomOrCreate(array $attributes = [])
 * @method static FileRepository|ProxyRepositoryDecorator repository()
 * @method static File[]|Proxy[]                          all()
 * @method static File[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static File[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static File[]|Proxy[]                          findBy(array $attributes)
 * @method static File[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static File[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class FileFactory extends PersistentProxyObjectFactory
{


    public static function class(): string
    {
        return File::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->text(255),
            'yourFileName' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(File $file): void {})
        ;
    }
}
