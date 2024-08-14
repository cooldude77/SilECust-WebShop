<?php

namespace App\Factory;

use App\Entity\OrderStatus;
use App\Repository\OrderStatusRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderStatus>
 *
 * @method        OrderStatus|Proxy                              create(array|callable $attributes = [])
 * @method static OrderStatus|Proxy                              createOne(array $attributes = [])
 * @method static OrderStatus|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderStatus|Proxy                              findOrCreate(array $attributes)
 * @method static OrderStatus|Proxy                              first(string $sortedField = 'id')
 * @method static OrderStatus|Proxy                              last(string $sortedField = 'id')
 * @method static OrderStatus|Proxy                              random(array $attributes = [])
 * @method static OrderStatus|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderStatusRepository|ProxyRepositoryDecorator repository()
 * @method static OrderStatus[]|Proxy[]                          all()
 * @method static OrderStatus[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderStatus[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderStatus[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderStatus[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderStatus[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderStatusFactory extends PersistentProxyObjectFactory
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
        return OrderStatus::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dateOfStatusSet' => self::faker()->dateTime(),
            'note' => self::faker()->text(),
            'orderHeader' => OrderHeaderFactory::new(),
            'orderStatusType' => OrderStatusTypeFactory::new(),
            'snapShot' => null, // TODO add OBJECT type manually
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderStatus $orderStatus): void {})
        ;
    }
}
