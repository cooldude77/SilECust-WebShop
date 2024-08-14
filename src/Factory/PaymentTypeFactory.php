<?php

namespace App\Factory;

use App\Entity\PaymentType;
use App\Repository\PaymentTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PaymentType>
 *
 * @method        PaymentType|Proxy                     create(array|callable $attributes = [])
 * @method static PaymentType|Proxy                     createOne(array $attributes = [])
 * @method static PaymentType|Proxy                     find(object|array|mixed $criteria)
 * @method static PaymentType|Proxy                     findOrCreate(array $attributes)
 * @method static PaymentType|Proxy                     first(string $sortedField = 'id')
 * @method static PaymentType|Proxy                     last(string $sortedField = 'id')
 * @method static PaymentType|Proxy                     random(array $attributes = [])
 * @method static PaymentType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PaymentTypeRepository|RepositoryProxy repository()
 * @method static PaymentType[]|Proxy[]                 all()
 * @method static PaymentType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PaymentType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PaymentType[]|Proxy[]                 findBy(array $attributes)
 * @method static PaymentType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PaymentType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PaymentTypeFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(PaymentType $paymentType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return PaymentType::class;
    }
}
