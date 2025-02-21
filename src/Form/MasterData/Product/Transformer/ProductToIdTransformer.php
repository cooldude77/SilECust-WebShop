<?php
// src/Form/DataTransformer/ProductToNumberTransformer.php
namespace Silecust\WebShop\Form\MasterData\Product\Transformer;

use Silecust\WebShop\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductToIdTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Transforms an object (product) to a string (number).
     *
     * @param Product|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (number) to an object (product).
     *
     * @param string $value
     *
     * @throws TransformationFailedException if object (product) is not found.
     */
    public function reverseTransform($value): ?Product
    {
        // no product number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        $product = $this->entityManager
            ->getRepository(Product::class)
            // query for the product with this id
            ->find($value);

        if (null === $product) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An product with number "%s" does not exist!',
                $value
            ));
        }

        return $product;
    }
}