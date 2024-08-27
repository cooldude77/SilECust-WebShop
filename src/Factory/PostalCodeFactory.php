<?php

namespace App\Factory;

use App\Entity\PostalCode;
use App\Repository\PostalCodeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PostalCode>
 *
 * @method        PostalCode|Proxy                     create(array|callable $attributes = [])
 * @method static PostalCode|Proxy                     createOne(array $attributes = [])
 * @method static PostalCode|Proxy                     find(object|array|mixed $criteria)
 * @method static PostalCode|Proxy                     findOrCreate(array $attributes)
 * @method static PostalCode|Proxy                     first(string $sortedField = 'id')
 * @method static PostalCode|Proxy                     last(string $sortedField = 'id')
 * @method static PostalCode|Proxy                     random(array $attributes = [])
 * @method static PostalCode|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PostalCodeRepository|RepositoryProxy repository()
 * @method static PostalCode[]|Proxy[]                 all()
 * @method static PostalCode[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PostalCode[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PostalCode[]|Proxy[]                 findBy(array $attributes)
 * @method static PostalCode[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PostalCode[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PostalCodeFactory extends ModelFactory
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
            'city' => CityFactory::new(),
            'postalCode' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(PostalCode $postalCode): void {})
        ;
    }

    protected static function getClass(): string
    {
        return PostalCode::class;
    }
}
