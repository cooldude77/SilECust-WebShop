<?php

namespace App\Factory;

use App\Entity\ProductGroup;
use App\Repository\ProductGroupRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductGroup>
 *
 * @method        ProductGroup|Proxy                     create(array|callable $attributes = [])
 * @method static ProductGroup|Proxy                     createOne(array $attributes = [])
 * @method static ProductGroup|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductGroup|Proxy                     findOrCreate(array $attributes)
 * @method static ProductGroup|Proxy                     first(string $sortedField = 'id')
 * @method static ProductGroup|Proxy                     last(string $sortedField = 'id')
 * @method static ProductGroup|Proxy                     random(array $attributes = [])
 * @method static ProductGroup|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductGroupRepository|RepositoryProxy repository()
 * @method static ProductGroup[]|Proxy[]                 all()
 * @method static ProductGroup[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductGroup[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductGroup[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductGroup[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductGroup[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ProductTypeFactory extends ModelFactory
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
            // ->afterInstantiate(function(ProductGroup $productType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductGroup::class;
    }
}
