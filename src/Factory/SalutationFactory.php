<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\Salutation;
use Silecust\WebShop\Repository\SalutationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Salutation>
 *
 * @method        Salutation|Proxy                     create(array|callable $attributes = [])
 * @method static Salutation|Proxy                     createOne(array $attributes = [])
 * @method static Salutation|Proxy                     find(object|array|mixed $criteria)
 * @method static Salutation|Proxy                     findOrCreate(array $attributes)
 * @method static Salutation|Proxy                     first(string $sortedField = 'id')
 * @method static Salutation|Proxy                     last(string $sortedField = 'id')
 * @method static Salutation|Proxy                     random(array $attributes = [])
 * @method static Salutation|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SalutationRepository|RepositoryProxy repository()
 * @method static Salutation[]|Proxy[]                 all()
 * @method static Salutation[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Salutation[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Salutation[]|Proxy[]                 findBy(array $attributes)
 * @method static Salutation[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Salutation[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SalutationFactory extends ModelFactory
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
            'description' => self::faker()->text(255),
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Salutation $salutation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Salutation::class;
    }
}
