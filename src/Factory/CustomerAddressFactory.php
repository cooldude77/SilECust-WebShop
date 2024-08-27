<?php

namespace App\Factory;

use App\Entity\CustomerAddress;
use App\Repository\CustomerAddressRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CustomerAddress>
 *
 * @method        CustomerAddress|Proxy                     create(array|callable $attributes = [])
 * @method static CustomerAddress|Proxy                     createOne(array $attributes = [])
 * @method static CustomerAddress|Proxy                     find(object|array|mixed $criteria)
 * @method static CustomerAddress|Proxy                     findOrCreate(array $attributes)
 * @method static CustomerAddress|Proxy                     first(string $sortedField = 'id')
 * @method static CustomerAddress|Proxy                     last(string $sortedField = 'id')
 * @method static CustomerAddress|Proxy                     random(array $attributes = [])
 * @method static CustomerAddress|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CustomerAddressRepository|RepositoryProxy repository()
 * @method static CustomerAddress[]|Proxy[]                 all()
 * @method static CustomerAddress[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CustomerAddress[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CustomerAddress[]|Proxy[]                 findBy(array $attributes)
 * @method static CustomerAddress[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CustomerAddress[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CustomerAddressFactory extends ModelFactory
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
            'line1' => self::faker()->text(255),
            'postalCode' => PostalCodeFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CustomerAddress $customerAddress): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CustomerAddress::class;
    }
}
