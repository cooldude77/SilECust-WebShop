<?php

namespace App\Factory;

use App\Entity\ProductAttributeKeyValue;
use App\Repository\ProductAttributeKeyValueRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductAttributeKeyValue>
 *
 * @method        ProductAttributeKeyValue|Proxy                     create(array|callable $attributes = [])
 * @method static ProductAttributeKeyValue|Proxy                     createOne(array $attributes = [])
 * @method static ProductAttributeKeyValue|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductAttributeKeyValue|Proxy                     findOrCreate(array $attributes)
 * @method static ProductAttributeKeyValue|Proxy                     first(string $sortedField = 'id')
 * @method static ProductAttributeKeyValue|Proxy                     last(string $sortedField = 'id')
 * @method static ProductAttributeKeyValue|Proxy                     random(array $attributes = [])
 * @method static ProductAttributeKeyValue|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductAttributeKeyValueRepository|RepositoryProxy repository()
 * @method static ProductAttributeKeyValue[]|Proxy[]                 all()
 * @method static ProductAttributeKeyValue[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductAttributeKeyValue[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductAttributeKeyValue[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductAttributeKeyValue[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductAttributeKeyValue[]|Proxy[]                 randomSet(int $number, array $attributes = [])
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
            // ->afterInstantiate(function(ProductAttributeKeyValue $productAttributeValue): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductAttributeKeyValue::class;
    }
}
