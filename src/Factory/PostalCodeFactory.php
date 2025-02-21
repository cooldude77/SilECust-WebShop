<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\PostalCode;
use Silecust\WebShop\Repository\PostalCodeRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<PostalCode>
 *
 * @method        PostalCode|Proxy                              create(array|callable $attributes = [])
 * @method static PostalCode|Proxy                              createOne(array $attributes = [])
 * @method static PostalCode|Proxy                              find(object|array|mixed $criteria)
 * @method static PostalCode|Proxy                              findOrCreate(array $attributes)
 * @method static PostalCode|Proxy                              first(string $sortedField = 'id')
 * @method static PostalCode|Proxy                              last(string $sortedField = 'id')
 * @method static PostalCode|Proxy                              random(array $attributes = [])
 * @method static PostalCode|Proxy                              randomOrCreate(array $attributes = [])
 * @method static PostalCodeRepository|ProxyRepositoryDecorator repository()
 * @method static PostalCode[]|Proxy[]                          all()
 * @method static PostalCode[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static PostalCode[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static PostalCode[]|Proxy[]                          findBy(array $attributes)
 * @method static PostalCode[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static PostalCode[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class PostalCodeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return PostalCode::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'city' => CityFactory::new(),
            'name' => self::faker()->text(255),
            'postalCode' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(PostalCode $postalCode): void {})
        ;
    }
}
