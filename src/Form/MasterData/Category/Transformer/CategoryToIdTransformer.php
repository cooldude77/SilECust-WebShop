<?php
// src/Form/DataTransformer/CategoryToNumberTransformer.php
namespace Silecust\WebShop\Form\MasterData\Category\Transformer;

use Silecust\WebShop\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryToIdTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * Transforms an object (category) to a string (number).
     *
     * @param  $value
     *
     * @return string
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (number) to an object (category).
     *
     * @param string $id
     * @throws TransformationFailedException if object (category) is not found.
     */
    public function reverseTransform($value): ?Category
    {
        // no category number? It's optional, so that's ok
        if ($value) {
            return null;
        }

        $category = $this->entityManager
            ->getRepository(Category::class)
            // query for the category with this id
            ->find($value);

        if (null === $category) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An category with number "%s" does not exist!',
                $value
            ));
        }

        return $category;
    }
}