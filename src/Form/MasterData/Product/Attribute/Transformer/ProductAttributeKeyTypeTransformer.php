<?php
// src/Form/DataTransformer/ProductAttributeKeyTypeToNumberTransformer.php
namespace App\Form\MasterData\Product\Attribute\Transformer;

use App\Entity\ProductAttributeKeyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductAttributeKeyTypeTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Transforms an object (ProductAttributeKeyType) to a string (number).
     *
     * @param ProductAttributeKeyType|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (number) to an object (ProductAttributeKeyType).
     *
     * @param string $value
     *
     * @throws TransformationFailedException if object (ProductAttributeKeyType) is not found.
     */
    public function reverseTransform($value): ?ProductAttributeKeyType
    {
        // no ProductAttributeKeyType number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        $ProductAttributeKeyType = $this->entityManager
            ->getRepository(ProductAttributeKeyType::class)
            // query for the ProductAttributeKeyType with this id
            ->find($value);

        if (null === $ProductAttributeKeyType) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An ProductAttributeKeyType with number "%s" does not exist!',
                $value
            ));
        }

        return $ProductAttributeKeyType;
    }
}