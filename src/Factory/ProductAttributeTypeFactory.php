<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\ProductAttributeType;
use Silecust\WebShop\Repository\ProductAttributeTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductAttributeType>
 *
 * @method        ProductAttributeType|Proxy                     create(array|callable $attributes = [])
 * @method static ProductAttributeType|Proxy                     createOne(array $attributes = [])
 * @method static ProductAttributeType|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductAttributeType|Proxy                     findOrCreate(array $attributes)
 * @method static ProductAttributeType|Proxy                     first(string $sortedField = 'id')
 * @method static ProductAttributeType|Proxy                     last(string $sortedField = 'id')
 * @method static ProductAttributeType|Proxy                     random(array $attributes = [])
 * @method static ProductAttributeType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductAttributeTypeRepository|RepositoryProxy repository()
 * @method static ProductAttributeType[]|Proxy[]                 all()
 * @method static ProductAttributeType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductAttributeType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductAttributeType[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductAttributeType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductAttributeType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ProductAttributeTypeFactory extends ModelFactory
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
            'type' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ProductAttributeType $productAttributeType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductAttributeType::class;
    }
}
