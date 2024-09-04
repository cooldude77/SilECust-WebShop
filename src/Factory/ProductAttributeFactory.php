<?php

namespace App\Factory;

use App\Entity\ProductAttributeKey;
use App\Repository\ProductAttributeKeyRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductAttributeKey>
 *
 * @method        ProductAttributeKey|Proxy                     create(array|callable $attributes = [])
 * @method static ProductAttributeKey|Proxy                     createOne(array $attributes = [])
 * @method static ProductAttributeKey|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductAttributeKey|Proxy                     findOrCreate(array $attributes)
 * @method static ProductAttributeKey|Proxy                     first(string $sortedField = 'id')
 * @method static ProductAttributeKey|Proxy                     last(string $sortedField = 'id')
 * @method static ProductAttributeKey|Proxy                     random(array $attributes = [])
 * @method static ProductAttributeKey|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductAttributeKeyRepository|RepositoryProxy repository()
 * @method static ProductAttributeKey[]|Proxy[]                 all()
 * @method static ProductAttributeKey[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductAttributeKey[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductAttributeKey[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductAttributeKey[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductAttributeKey[]|Proxy[]                 randomSet(int $number, array $attributes = [])
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
            // ->afterInstantiate(function(ProductAttributeKey $productAttribute): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductAttributeKey::class;
    }
}
