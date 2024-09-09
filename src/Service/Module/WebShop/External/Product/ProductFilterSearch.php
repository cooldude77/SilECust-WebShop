<?php

namespace App\Service\Module\WebShop\External\Product;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductAttributeKeyValue;
use App\Entity\ProductGroupAttributeKey;
use App\Entity\ProductGroupAttributeKeyValueVisiblity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Product is connected to product group
 * Product Group has many relations with attributes
 * Product Attribute is connected to values
 *
 * All of them are made valid and visible in the ProductGroupAttributeKeyValueVisibility table
 */
readonly class ProductFilterSearch implements ProductFilterSearchInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function searchByFilter(Category $category, $filterKeyValueArray): array
    {


        $attributeKeyIds = array_keys($filterKeyValueArray);
        $attributeValueIds = array_values($filterKeyValueArray);

        $values = $this->entityManager->createQueryBuilder()
            ->select('pakv', 'pak')
            ->from(ProductAttributeKeyValue::class, 'pakv')
            ->join('pakv.productAttributeKey', 'pak')
            ->where('pak.id in (:keyIds)')
            ->andWhere('pakv.id in (:valueIds)')
            ->setParameter('keyIds', $attributeKeyIds)
            ->setParameter('valueIds', $attributeValueIds)
            ->getQuery()
            ->getResult();

        $keys = [];
        /** @var ProductAttributeKeyValue $value */
        foreach ($values as $value) {
            $keys = $value->getProductAttributeKey();
        }
        // Now that we have valid keys
        // Get group
        $productAttributeGroups = $this->entityManager->createQueryBuilder()
            ->select('pgak', 'pg')
            ->from(ProductGroupAttributeKey::class, 'pgak')
            ->join('pgak.productGroup', 'pg')
            ->where('pgak in (:keys)')
            ->setParameter('keys', $keys)
            ->getQuery()
            ->getResult();

        $productGroups = [];
        /** @var ProductGroupAttributeKey $productAttributeGroupKey */
        foreach ($productAttributeGroups as $productAttributeGroupKey) {
            $productGroups[] = $productAttributeGroupKey->getProductGroup();
        }

        // Need to search from other table
        $products = $this->entityManager->createQueryBuilder()
            ->select('pr')
            ->from(Product::class, 'pr')
            ->where('pr.productGroup in (:groups)')
            ->andWhere('pr.category in (:categories)')
            ->setParameter('groups', $productGroups)
            ->setParameter('categories', [$category]) // todo: when the hierarchy function completes in master data,
            // replace this with all children
            ->getQuery()
            ->getResult();


        $productGroupAttributeVisibility = $this->entityManager->createQueryBuilder()
            ->select('pgakvv', 'p')
            ->from(ProductGroupAttributeKeyValueVisiblity::class, 'pgakvv')
            ->join('pgakvv.product', 'p')
            ->join('pgakvv.productGroup', 'pg')
            ->join('pgakvv.productAttributeKey', 'pak')
            ->join('pgakvv.productAttributeKeyValue', 'pakv')
            ->where('p in (:products) AND pg in (:productGroups)')
            ->andWhere('pg in (:productGroups) and pak in (:keys)')
            ->andWhere('pak in (:keys) and pakv in (:values)')
            ->andWhere('pgakvv.isAvailable = true')
            ->setParameter('products', $products)
            ->setParameter('productGroups', $productGroups)
            ->setParameter('keys', $keys)
            ->setParameter('values', $value)
            ->distinct()
            ->getQuery()
            ->getResult();


        $return = new ArrayCollection();

        /** @var ProductGroupAttributeKeyValueVisiblity $item */
        foreach ($productGroupAttributeVisibility as $item) {
            if (!$return->contains($item->getProduct()))
                $return->add($item->getProduct());
        }
        return $return->toArray();
    }

}