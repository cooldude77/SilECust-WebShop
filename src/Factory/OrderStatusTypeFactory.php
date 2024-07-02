<?php

namespace App\Factory;

use App\Entity\OrderStatusType;
use App\Repository\OrderStatusTypeRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderStatusType>
 *
 * @method        OrderStatusType|Proxy                              create(array|callable $attributes = [])
 * @method static OrderStatusType|Proxy                              createOne(array $attributes = [])
 * @method static OrderStatusType|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderStatusType|Proxy                              findOrCreate(array $attributes)
 * @method static OrderStatusType|Proxy                              first(string $sortedField = 'id')
 * @method static OrderStatusType|Proxy                              last(string $sortedField = 'id')
 * @method static OrderStatusType|Proxy                              random(array $attributes = [])
 * @method static OrderStatusType|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderStatusTypeRepository|ProxyRepositoryDecorator repository()
 * @method static OrderStatusType[]|Proxy[]                          all()
 * @method static OrderStatusType[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderStatusType[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderStatusType[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderStatusType[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderStatusType[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderStatusTypeFactory extends PersistentProxyObjectFactory
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
        return OrderStatusType::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'description' => self::faker()->text(255),
            'type' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderStatusType $orderStatusType): void {})
        ;
    }
}
