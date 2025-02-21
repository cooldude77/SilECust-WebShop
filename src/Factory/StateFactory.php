<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\State;
use Silecust\WebShop\Repository\StateRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<State>
 *
 * @method        State|Proxy                     create(array|callable $attributes = [])
 * @method static State|Proxy                     createOne(array $attributes = [])
 * @method static State|Proxy                     find(object|array|mixed $criteria)
 * @method static State|Proxy                     findOrCreate(array $attributes)
 * @method static State|Proxy                     first(string $sortedField = 'id')
 * @method static State|Proxy                     last(string $sortedField = 'id')
 * @method static State|Proxy                     random(array $attributes = [])
 * @method static State|Proxy                     randomOrCreate(array $attributes = [])
 * @method static StateRepository|RepositoryProxy repository()
 * @method static State[]|Proxy[]                 all()
 * @method static State[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static State[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static State[]|Proxy[]                 findBy(array $attributes)
 * @method static State[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static State[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class StateFactory extends ModelFactory
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
            'country' => CountryFactory::new(),
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(State $state): void {})
        ;
    }

    protected static function getClass(): string
    {
        return State::class;
    }
}
