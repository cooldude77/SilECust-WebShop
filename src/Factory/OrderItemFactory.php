<?php

namespace App\Factory;

use App\Entity\OrderItem;
use App\Repository\OrderItemRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderItem>
 *
 * @method        OrderItem|Proxy                              create(array|callable $attributes = [])
 * @method static OrderItem|Proxy                              createOne(array $attributes = [])
 * @method static OrderItem|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderItem|Proxy                              findOrCreate(array $attributes)
 * @method static OrderItem|Proxy                              first(string $sortedField = 'id')
 * @method static OrderItem|Proxy                              last(string $sortedField = 'id')
 * @method static OrderItem|Proxy                              random(array $attributes = [])
 * @method static OrderItem|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderItemRepository|ProxyRepositoryDecorator repository()
 * @method static OrderItem[]|Proxy[]                          all()
 * @method static OrderItem[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderItem[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderItem[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderItem[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderItem[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderItemFactory extends PersistentProxyObjectFactory
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
        return OrderItem::class;
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
            'pricePerUnit' => self::faker()->randomFloat(),
            'product' => ProductFactory::new(),
            'quantity' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderItem $orderItem): void {})
        ;
    }
}
