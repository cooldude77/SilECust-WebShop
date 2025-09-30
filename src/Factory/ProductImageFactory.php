<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Factory\FileFactory;
use Silecust\WebShop\Factory\ProductFactory;
use Silecust\WebShop\Repository\ProductImageRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<ProductImage>
 *
 * @method        ProductImage|Proxy                              create(array|callable $attributes = [])
 * @method static ProductImage|Proxy                              createOne(array $attributes = [])
 * @method static ProductImage|Proxy                              find(object|array|mixed $criteria)
 * @method static ProductImage|Proxy                              findOrCreate(array $attributes)
 * @method static ProductImage|Proxy                              first(string $sortedField = 'id')
 * @method static ProductImage|Proxy                              last(string $sortedField = 'id')
 * @method static ProductImage|Proxy                              random(array $attributes = [])
 * @method static ProductImage|Proxy                              randomOrCreate(array $attributes = [])
 * @method static ProductImageRepository|ProxyRepositoryDecorator repository()
 * @method static ProductImage[]|Proxy[]                          all()
 * @method static ProductImage[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static ProductImage[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static ProductImage[]|Proxy[]                          findBy(array $attributes)
 * @method static ProductImage[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductImage[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class ProductImageFactory extends PersistentProxyObjectFactory
{


    public static function class(): string
    {
        return ProductImage::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function defaults(): array|callable
    {
        return [
            'file' => FileFactory::new(),
            'product' => ProductFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ProductImage $productImage): void {})
        ;
    }
}
