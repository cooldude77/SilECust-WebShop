<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\OrderItemPaymentPrice;
use Silecust\WebShop\Factory\OrderItemFactory;
use Silecust\WebShop\Repository\OrderItemPaymentPriceRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderItemPaymentPrice>
 *
 * @method        OrderItemPaymentPrice|Proxy                              create(array|callable $attributes = [])
 * @method static OrderItemPaymentPrice|Proxy                              createOne(array $attributes = [])
 * @method static OrderItemPaymentPrice|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderItemPaymentPrice|Proxy                              findOrCreate(array $attributes)
 * @method static OrderItemPaymentPrice|Proxy                              first(string $sortedField = 'id')
 * @method static OrderItemPaymentPrice|Proxy                              last(string $sortedField = 'id')
 * @method static OrderItemPaymentPrice|Proxy                              random(array $attributes = [])
 * @method static OrderItemPaymentPrice|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderItemPaymentPriceRepository|ProxyRepositoryDecorator repository()
 * @method static OrderItemPaymentPrice[]|Proxy[]                          all()
 * @method static OrderItemPaymentPrice[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderItemPaymentPrice[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderItemPaymentPrice[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderItemPaymentPrice[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderItemPaymentPrice[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderItemPaymentPriceFactory extends PersistentProxyObjectFactory
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
        return OrderItemPaymentPrice::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'basePrice' => self::faker()->randomFloat(),
            'basePriceInJson' => [],
            'discountsInJson' => [],
            'orderItem' => OrderItemFactory::new(),
            'taxRate' => self::faker()->randomFloat(),
            'taxationInJson' => [],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderItemPaymentPrice $orderItemPaymentPrice): void {})
        ;
    }
}
