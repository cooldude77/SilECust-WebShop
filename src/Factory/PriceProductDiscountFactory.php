<?php

namespace App\Factory;

use App\Entity\PriceProductDiscount;
use App\Repository\PriceProductDiscountRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<PriceProductDiscount>
 *
 * @method        PriceProductDiscount|Proxy                              create(array|callable $attributes = [])
 * @method static PriceProductDiscount|Proxy                              createOne(array $attributes = [])
 * @method static PriceProductDiscount|Proxy                              find(object|array|mixed $criteria)
 * @method static PriceProductDiscount|Proxy                              findOrCreate(array $attributes)
 * @method static PriceProductDiscount|Proxy                              first(string $sortedField = 'id')
 * @method static PriceProductDiscount|Proxy                              last(string $sortedField = 'id')
 * @method static PriceProductDiscount|Proxy                              random(array $attributes = [])
 * @method static PriceProductDiscount|Proxy                              randomOrCreate(array $attributes = [])
 * @method static PriceProductDiscountRepository|ProxyRepositoryDecorator repository()
 * @method static PriceProductDiscount[]|Proxy[]                          all()
 * @method static PriceProductDiscount[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static PriceProductDiscount[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static PriceProductDiscount[]|Proxy[]                          findBy(array $attributes)
 * @method static PriceProductDiscount[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static PriceProductDiscount[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class PriceProductDiscountFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */

    public static function class(): string
    {
        return PriceProductDiscount::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'currency' => CurrencyFactory::new(),
            'product' => ProductFactory::new(),
            'value' => self::faker()->randomFloat(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(PriceProductDiscount $priceProductDiscount): void {})
        ;
    }
}
