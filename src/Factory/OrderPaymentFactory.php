<?php

namespace App\Factory;

use App\Entity\OrderPayment;
use App\Repository\OrderPaymentRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderPayment>
 *
 * @method        OrderPayment|Proxy                              create(array|callable $attributes = [])
 * @method static OrderPayment|Proxy                              createOne(array $attributes = [])
 * @method static OrderPayment|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderPayment|Proxy                              findOrCreate(array $attributes)
 * @method static OrderPayment|Proxy                              first(string $sortedField = 'id')
 * @method static OrderPayment|Proxy                              last(string $sortedField = 'id')
 * @method static OrderPayment|Proxy                              random(array $attributes = [])
 * @method static OrderPayment|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderPaymentRepository|ProxyRepositoryDecorator repository()
 * @method static OrderPayment[]|Proxy[]                          all()
 * @method static OrderPayment[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderPayment[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderPayment[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderPayment[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderPayment[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderPaymentFactory extends PersistentProxyObjectFactory
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
        return OrderPayment::class;
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
            'paymentDetails' => [],
            'status' => self::faker()->boolean(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderPayment $orderPayment): void {})
        ;
    }
}
