<?php

namespace App\Factory;

use App\Entity\PriceProductBase;
use App\Repository\PriceProductBaseRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PriceProductBase>
 *
 * @method        PriceProductBase|Proxy                     create(array|callable $attributes = [])
 * @method static PriceProductBase|Proxy                     createOne(array $attributes = [])
 * @method static PriceProductBase|Proxy                     find(object|array|mixed $criteria)
 * @method static PriceProductBase|Proxy                     findOrCreate(array $attributes)
 * @method static PriceProductBase|Proxy                     first(string $sortedField = 'id')
 * @method static PriceProductBase|Proxy                     last(string $sortedField = 'id')
 * @method static PriceProductBase|Proxy                     random(array $attributes = [])
 * @method static PriceProductBase|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PriceProductBaseRepository|RepositoryProxy repository()
 * @method static PriceProductBase[]|Proxy[]                 all()
 * @method static PriceProductBase[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PriceProductBase[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PriceProductBase[]|Proxy[]                 findBy(array $attributes)
 * @method static PriceProductBase[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PriceProductBase[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PriceProductBaseFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'currency' => CurrencyFactory::new(),
            'price' => self::faker()->randomFloat(),
            'product' => ProductFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(PriceBase $priceBase): void {})
        ;
    }

    protected static function getClass(): string
    {
        return PriceProductBase::class;
    }
}
