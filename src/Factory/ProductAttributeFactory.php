<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\ProductAttribute;
use Silecust\WebShop\Repository\ProductAttributeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductAttribute>
 *
 * @method        ProductAttribute|Proxy                     create(array|callable $attributes = [])
 * @method static ProductAttribute|Proxy                     createOne(array $attributes = [])
 * @method static ProductAttribute|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductAttribute|Proxy                     findOrCreate(array $attributes)
 * @method static ProductAttribute|Proxy                     first(string $sortedField = 'id')
 * @method static ProductAttribute|Proxy                     last(string $sortedField = 'id')
 * @method static ProductAttribute|Proxy                     random(array $attributes = [])
 * @method static ProductAttribute|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductAttributeRepository|RepositoryProxy repository()
 * @method static ProductAttribute[]|Proxy[]                 all()
 * @method static ProductAttribute[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductAttribute[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductAttribute[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductAttribute[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductAttribute[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ProductAttributeFactory extends ModelFactory
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
            'productAttributeType' => ProductAttributeTypeFactory::new(),
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
            // ->afterInstantiate(function(ProductAttribute $productAttribute): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductAttribute::class;
    }
}
