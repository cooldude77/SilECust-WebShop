<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\ProductType;
use Silecust\WebShop\Repository\ProductTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductType>
 *
 * @method        ProductType|Proxy                     create(array|callable $attributes = [])
 * @method static ProductType|Proxy                     createOne(array $attributes = [])
 * @method static ProductType|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductType|Proxy                     findOrCreate(array $attributes)
 * @method static ProductType|Proxy                     first(string $sortedField = 'id')
 * @method static ProductType|Proxy                     last(string $sortedField = 'id')
 * @method static ProductType|Proxy                     random(array $attributes = [])
 * @method static ProductType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductTypeRepository|RepositoryProxy repository()
 * @method static ProductType[]|Proxy[]                 all()
 * @method static ProductType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductType[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
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
            // ->afterInstantiate(function(ProductType $productType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductType::class;
    }
}
