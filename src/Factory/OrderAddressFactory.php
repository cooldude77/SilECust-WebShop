<?php

namespace App\Factory;

use App\Entity\OrderAddress;
use App\Repository\OrderAddressRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderAddress>
 *
 * @method        OrderAddress|Proxy                              create(array|callable $attributes = [])
 * @method static OrderAddress|Proxy                              createOne(array $attributes = [])
 * @method static OrderAddress|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderAddress|Proxy                              findOrCreate(array $attributes)
 * @method static OrderAddress|Proxy                              first(string $sortedField = 'id')
 * @method static OrderAddress|Proxy                              last(string $sortedField = 'id')
 * @method static OrderAddress|Proxy                              random(array $attributes = [])
 * @method static OrderAddress|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderAddressRepository|ProxyRepositoryDecorator repository()
 * @method static OrderAddress[]|Proxy[]                          all()
 * @method static OrderAddress[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderAddress[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderAddress[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderAddress[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderAddress[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderAddressFactory extends PersistentProxyObjectFactory
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
        return OrderAddress::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'orderHeader' => OrderHeaderFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderAddress $orderAddress): void {})
        ;
    }
}
