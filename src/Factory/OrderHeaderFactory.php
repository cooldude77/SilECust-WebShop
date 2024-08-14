<?php

namespace App\Factory;

use App\Entity\OrderHeader;
use App\Repository\OrderHeaderRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<OrderHeader>
 *
 * @method        OrderHeader|Proxy                     create(array|callable $attributes = [])
 * @method static OrderHeader|Proxy                     createOne(array $attributes = [])
 * @method static OrderHeader|Proxy                     find(object|array|mixed $criteria)
 * @method static OrderHeader|Proxy                     findOrCreate(array $attributes)
 * @method static OrderHeader|Proxy                     first(string $sortedField = 'id')
 * @method static OrderHeader|Proxy                     last(string $sortedField = 'id')
 * @method static OrderHeader|Proxy                     random(array $attributes = [])
 * @method static OrderHeader|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OrderHeaderRepository|RepositoryProxy repository()
 * @method static OrderHeader[]|Proxy[]                 all()
 * @method static OrderHeader[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static OrderHeader[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static OrderHeader[]|Proxy[]                 findBy(array $attributes)
 * @method static OrderHeader[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderHeader[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OrderHeaderFactory extends ModelFactory
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
            'customer' => CustomerFactory::new(),
            'dateTimeOfOrder' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(OrderHeader $orderHeader): void {})
        ;
    }

    protected static function getClass(): string
    {
        return OrderHeader::class;
    }
}
