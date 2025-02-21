<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\ProductAttributeValue;
use Silecust\WebShop\Repository\ProductAttributeValueRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductAttributeValue>
 *
 * @method        ProductAttributeValue|Proxy                     create(array|callable $attributes = [])
 * @method static ProductAttributeValue|Proxy                     createOne(array $attributes = [])
 * @method static ProductAttributeValue|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductAttributeValue|Proxy                     findOrCreate(array $attributes)
 * @method static ProductAttributeValue|Proxy                     first(string $sortedField = 'id')
 * @method static ProductAttributeValue|Proxy                     last(string $sortedField = 'id')
 * @method static ProductAttributeValue|Proxy                     random(array $attributes = [])
 * @method static ProductAttributeValue|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductAttributeValueRepository|RepositoryProxy repository()
 * @method static ProductAttributeValue[]|Proxy[]                 all()
 * @method static ProductAttributeValue[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductAttributeValue[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductAttributeValue[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductAttributeValue[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductAttributeValue[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ProductAttributeValueFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
            'productAttribute' => ProductAttributeFactory::new(),
            'value' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ProductAttributeValue $productAttributeValue): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductAttributeValue::class;
    }
}
