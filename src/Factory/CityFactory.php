<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\City;
use Silecust\WebShop\Repository\CityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<City>
 *
 * @method        City|Proxy                     create(array|callable $attributes = [])
 * @method static City|Proxy                     createOne(array $attributes = [])
 * @method static City|Proxy                     find(object|array|mixed $criteria)
 * @method static City|Proxy                     findOrCreate(array $attributes)
 * @method static City|Proxy                     first(string $sortedField = 'id')
 * @method static City|Proxy                     last(string $sortedField = 'id')
 * @method static City|Proxy                     random(array $attributes = [])
 * @method static City|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CityRepository|RepositoryProxy repository()
 * @method static City[]|Proxy[]                 all()
 * @method static City[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static City[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static City[]|Proxy[]                 findBy(array $attributes)
 * @method static City[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static City[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CityFactory extends ModelFactory
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
            'code' => self::faker()->text(255),
            'name' => self::faker()->text(255),
            'state' => StateFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(City $city): void {})
        ;
    }

    protected static function getClass(): string
    {
        return City::class;
    }
}
