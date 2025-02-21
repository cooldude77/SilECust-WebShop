<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\InventoryProduct;
use Silecust\WebShop\Repository\InventoryProductRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<InventoryProduct>
 *
 * @method        InventoryProduct|Proxy                     create(array|callable $attributes = [])
 * @method static InventoryProduct|Proxy                     createOne(array $attributes = [])
 * @method static InventoryProduct|Proxy                     find(object|array|mixed $criteria)
 * @method static InventoryProduct|Proxy                     findOrCreate(array $attributes)
 * @method static InventoryProduct|Proxy                     first(string $sortedField = 'id')
 * @method static InventoryProduct|Proxy                     last(string $sortedField = 'id')
 * @method static InventoryProduct|Proxy                     random(array $attributes = [])
 * @method static InventoryProduct|Proxy                     randomOrCreate(array $attributes = [])
 * @method static InventoryProductRepository|RepositoryProxy repository()
 * @method static InventoryProduct[]|Proxy[]                 all()
 * @method static InventoryProduct[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static InventoryProduct[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static InventoryProduct[]|Proxy[]                 findBy(array $attributes)
 * @method static InventoryProduct[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static InventoryProduct[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class InventoryProductFactory extends ModelFactory
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
            'product' => ProductFactory::new(),
            'quantity' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(InventoryProduct $inventoryProduct): void {})
        ;
    }

    protected static function getClass(): string
    {
        return InventoryProduct::class;
    }
}
