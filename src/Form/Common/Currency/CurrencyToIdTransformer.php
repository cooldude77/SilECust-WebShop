<?php
// src/Form/DataTransformer/CurrencyToNumberTransformer.php
namespace Silecust\WebShop\Form\Common\Currency;

use Silecust\WebShop\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CurrencyToIdTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Transforms an object (currency) to a string (number).
     *
     * @param Currency|null $currency
     */
    public function transform($currency): string
    {
        if (null === $currency) {
            return '';
        }

        return $currency->getId();
    }

    /**
     * Transforms a string (number) to an object (currency).
     *
     * @param string $value
     *
     * @throws TransformationFailedException if object (currency) is not found.
     */
    public function reverseTransform($value): ?Currency
    {
        // no currency number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        $currency = $this->entityManager
            ->getRepository(Currency::class)
            // query for the currency with this id
            ->find($value);

        if (null === $currency) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An currency with number "%s" does not exist!',
                $value
            ));
        }

        return $currency;
    }
}