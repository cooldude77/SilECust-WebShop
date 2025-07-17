<?php

namespace Silecust\WebShop\Factory;

use Silecust\WebShop\Entity\OrderJournal;
use Silecust\WebShop\Repository\OrderJournalRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<OrderJournal>
 *
 * @method        OrderJournal|Proxy                              create(array|callable $attributes = [])
 * @method static OrderJournal|Proxy                              createOne(array $attributes = [])
 * @method static OrderJournal|Proxy                              find(object|array|mixed $criteria)
 * @method static OrderJournal|Proxy                              findOrCreate(array $attributes)
 * @method static OrderJournal|Proxy                              first(string $sortedField = 'id')
 * @method static OrderJournal|Proxy                              last(string $sortedField = 'id')
 * @method static OrderJournal|Proxy                              random(array $attributes = [])
 * @method static OrderJournal|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderJournalRepository|ProxyRepositoryDecorator repository()
 * @method static OrderJournal[]|Proxy[]                          all()
 * @method static OrderJournal[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static OrderJournal[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static OrderJournal[]|Proxy[]                          findBy(array $attributes)
 * @method static OrderJournal[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderJournal[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderJournalFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return OrderJournal::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'changeNote' => self::faker()->text(),
            'createdAt' => self::faker()->dateTime(),
            'orderHeader' => OrderHeaderFactory::new(),
            'orderSnapShot' => [],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(OrderJournal $orderJournal): void {})
        ;
    }
}
