<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Repository\OrderShippingRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderShipping>
 *
 * @method        OrderShipping|Proxy                              create(array|callable $attributes = [])
 * @method static OrderShipping|Proxy                              createOne(array $attributes = [])
 * @method static OrderShipping|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderShipping|Proxy                              findOrCreate(array $attributes)
 * @method static OrderShipping|Proxy                              first(string $sortedField = 'id')
 * @method static OrderShipping|Proxy                              last(string $sortedField = 'id')
 * @method static OrderShipping|Proxy                              random(array $attributes = [])
 * @method static OrderShipping|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderShippingRepository|ProxyRepositoryDecorator repository()
 * @method static OrderShipping[]|Proxy[]                          all()
 * @method static OrderShipping[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderShipping[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderShipping[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderShipping[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderShipping[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderShippingFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return OrderShipping::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'data' => [],
            'name' => self::faker()->text(255),
            'value' => self::faker()->randomFloat(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderShipping $orderShipping): void {})
        ;
    }
}
