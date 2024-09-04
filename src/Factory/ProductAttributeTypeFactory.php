<?php

namespace App\Factory;

use App\Entity\ProductAttributeKeyType;
use App\Repository\ProductAttributeKeyTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProductAttributeKeyType>
 *
 * @method        ProductAttributeKeyType|Proxy                     create(array|callable $attributes = [])
 * @method static ProductAttributeKeyType|Proxy                     createOne(array $attributes = [])
 * @method static ProductAttributeKeyType|Proxy                     find(object|array|mixed $criteria)
 * @method static ProductAttributeKeyType|Proxy                     findOrCreate(array $attributes)
 * @method static ProductAttributeKeyType|Proxy                     first(string $sortedField = 'id')
 * @method static ProductAttributeKeyType|Proxy                     last(string $sortedField = 'id')
 * @method static ProductAttributeKeyType|Proxy                     random(array $attributes = [])
 * @method static ProductAttributeKeyType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProductAttributeKeyTypeRepository|RepositoryProxy repository()
 * @method static ProductAttributeKeyType[]|Proxy[]                 all()
 * @method static ProductAttributeKeyType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProductAttributeKeyType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ProductAttributeKeyType[]|Proxy[]                 findBy(array $attributes)
 * @method static ProductAttributeKeyType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductAttributeKeyType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
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
            // ->afterInstantiate(function(ProductAttributeKeyType $productAttributeType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProductAttributeKeyType::class;
    }
}
