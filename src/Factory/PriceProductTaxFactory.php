<?php

namespace App\Factory;

use App\Entity\PriceProductTax;
use App\Repository\PriceProductTaxRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<PriceProductTax>
 *
 * @method        PriceProductTax|Proxy                              create(array|callable $attributes = [])
 * @method static PriceProductTax|Proxy                              createOne(array $attributes = [])
 * @method static PriceProductTax|Proxy                              find(object|array|mixed $criteria)
 * @method static PriceProductTax|Proxy                              findOrCreate(array $attributes)
 * @method static PriceProductTax|Proxy                              first(string $sortedField = 'id')
 * @method static PriceProductTax|Proxy                              last(string $sortedField = 'id')
 * @method static PriceProductTax|Proxy                              random(array $attributes = [])
 * @method static PriceProductTax|Proxy                              randomOrCreate(array $attributes = [])
 * @method static PriceProductTaxRepository|ProxyRepositoryDecorator repository()
 * @method static PriceProductTax[]|Proxy[]                          all()
 * @method static PriceProductTax[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static PriceProductTax[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static PriceProductTax[]|Proxy[]                          findBy(array $attributes)
 * @method static PriceProductTax[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static PriceProductTax[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class PriceProductTaxFactory extends PersistentProxyObjectFactory
{


    public static function class(): string
    {
        return PriceProductTax::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'product' => ProductFactory::new(),
            'taxSlab' => TaxSlabFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(PriceProductTax $priceProductTax): void {})
        ;
    }
}
