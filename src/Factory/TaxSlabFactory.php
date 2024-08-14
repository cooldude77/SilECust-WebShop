<?php

namespace App\Factory;

use App\Entity\TaxSlab;
use App\Repository\TaxSlabRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<TaxSlab>
 *
 * @method        TaxSlab|Proxy                              create(array|callable $attributes = [])
 * @method static TaxSlab|Proxy                              createOne(array $attributes = [])
 * @method static TaxSlab|Proxy                              find(object|array|mixed $criteria)
 * @method static TaxSlab|Proxy                              findOrCreate(array $attributes)
 * @method static TaxSlab|Proxy                              first(string $sortedField = 'id')
 * @method static TaxSlab|Proxy                              last(string $sortedField = 'id')
 * @method static TaxSlab|Proxy                              random(array $attributes = [])
 * @method static TaxSlab|Proxy                              randomOrCreate(array $attributes = [])
 * @method static TaxSlabRepository|ProxyRepositoryDecorator repository()
 * @method static TaxSlab[]|Proxy[]                          all()
 * @method static TaxSlab[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static TaxSlab[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static TaxSlab[]|Proxy[]                          findBy(array $attributes)
 * @method static TaxSlab[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static TaxSlab[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class TaxSlabFactory extends PersistentProxyObjectFactory
{


    public static function class(): string
    {
        return TaxSlab::class;
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
            'name' => self::faker()->text(255),
            'rateOfTax' => self::faker()->randomFloat(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(TaxSlab $taxSlab): void {})
        ;
    }
}
