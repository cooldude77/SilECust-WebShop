<?php
// src/Form/DataTransformer/CustomerToNumberTransformer.php
namespace Silecust\WebShop\Form\MasterData\Customer\Transformer;

use Silecust\WebShop\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CustomerToIdTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Transforms an object (customer) to a string (number).
     *
     * @param Customer|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (number) to an object (customer).
     *
     * @param string $value
     *
     * @throws TransformationFailedException if object (customer) is not found.
     */
    public function reverseTransform($value): ?Customer
    {
        // no customer number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        $customer = $this->entityManager
            ->getRepository(Customer::class)
            // query for the customer with this id
            ->find($value);

        if (null === $customer) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An customer with number "%s" does not exist!',
                $value
            ));
        }

        return $customer;
    }
}