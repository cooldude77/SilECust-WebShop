<?php

namespace App\Service\MasterData\Product\Filter\Provider;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductAttributeKeyValue;
use App\Entity\ProductGroupAttributeKey;
use Doctrine\ORM\EntityManagerInterface;

class ProductFilterProvider implements ProductFilterProviderInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    public function getList(Category $category): array
    {
        /*
         *
         select * from product_attribute_key_value v
   join product_attribute_key k on k.id = v.product_attribute_key_id
   join product_group_attribute_key pgak on pgak.product_attribute_key_id = k.id
   join product_group pg on pgak.product_group_id = pg.id
   join product pr on pr.product_group_id = pg.id
   where pr.category_id = 1
         */

        $em = $this->entityManager;
        $result = $em->createQueryBuilder()
            ->select('pr')
            ->from(Product::class, 'pr')
            ->join('pr.category', 'c')
            ->where('c=:category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();

        $groups = array();
        /** @var Product $product */
        foreach ($result as $product) {
            $groups[] = $product->getProductGroup();
        }

        $pgaks = $em->createQueryBuilder()
            ->select('pgak')
            ->from(ProductGroupAttributeKey::class, 'pgak')
            ->join('pgak.productAttributeKey', 'pak')
            ->where('pgak.productGroup in (:groups)')
            ->setParameter("groups", $groups)
            ->getQuery()
            ->getResult();

        $keys = array();
        /** @var ProductGroupAttributeKey $pgak */
        foreach ($pgaks as $pgak) {
            $keys[] = $pgak->getProductAttributeKey();
        }

        $values = $em->createQueryBuilder()
            ->select('pakv')
            ->from(ProductAttributeKeyValue::class, 'pakv')
            ->where('pakv.productAttributeKey in (:keys)')
            ->setParameter("keys", $keys)
            ->getQuery()
            ->getResult();


        $return = [];
        /** @var ProductAttributeKeyValue $value */
        foreach ($values as $value) {
            $return[$value->getProductAttributeKey()->getId()][] = array('key' => $value->getProductAttributeKey(), 'value' => $value);
        }

        return $return;
    }
}