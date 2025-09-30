<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Factory\FileFactory;
use Silecust\WebShop\Repository\CategoryImageRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<CategoryImage>
 *
 * @method        CategoryImage|Proxy                              create(array|callable $attributes = [])
 * @method static CategoryImage|Proxy                              createOne(array $attributes = [])
 * @method static CategoryImage|Proxy                              find(object|array|mixed $criteria)
 * @method static CategoryImage|Proxy                              findOrCreate(array $attributes)
 * @method static CategoryImage|Proxy                              first(string $sortedField = 'id')
 * @method static CategoryImage|Proxy                              last(string $sortedField = 'id')
 * @method static CategoryImage|Proxy                              random(array $attributes = [])
 * @method static CategoryImage|Proxy                              randomOrCreate(array $attributes = [])
 * @method static CategoryImageRepository|ProxyRepositoryDecorator repository()
 * @method static CategoryImage[]|Proxy[]                          all()
 * @method static CategoryImage[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static CategoryImage[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static CategoryImage[]|Proxy[]                          findBy(array $attributes)
 * @method static CategoryImage[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static CategoryImage[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class CategoryImageFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return CategoryImage::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function defaults(): array|callable
    {
        return [
            'category' => CategoryFactory::new(),
            'file' => FileFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(CategoryImage $categoryImage): void {})
        ;
    }
}
