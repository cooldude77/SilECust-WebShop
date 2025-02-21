<?php
// src/Form/DataTransformer/ProductAttributeTypeToNumberTransformer.php
namespace Silecust\WebShop\Form\MasterData\Product\Attribute\Transformer;

use Silecust\WebShop\Entity\ProductAttributeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductAttributeTypeTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Transforms an object (productAttributeType) to a string (number).
     *
     * @param ProductAttributeType|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (number) to an object (productAttributeType).
     *
     * @param string $value
     *
     * @throws TransformationFailedException if object (productAttributeType) is not found.
     */
    public function reverseTransform($value): ?ProductAttributeType
    {
        // no productAttributeType number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        $productAttributeType = $this->entityManager
            ->getRepository(ProductAttributeType::class)
            // query for the productAttributeType with this id
            ->find($value);

        if (null === $productAttributeType) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An productAttributeType with number "%s" does not exist!',
                $value
            ));
        }

        return $productAttributeType;
    }
}