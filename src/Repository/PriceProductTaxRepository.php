<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\PriceProductTax;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Entity\TaxSlab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PriceProductTax>
 */
class PriceProductTaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceProductTax::class);
    }

//    /**
//     * @return PriceProductTax[] Returns an array of PriceProductTax objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PriceProductTax
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findProductByTaxSlabs(Product $product, array $taxSlabs): mixed
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('ppt')
            ->from(PriceProductTax::class, 'ppt')
            ->where('ppt.product =:product')
            ->andWhere("ppt.taxSlab IN  (:taxSlabs)")
            ->setParameter('product', $product)
            ->setParameter('taxSlabs', $taxSlabs)
            ->getQuery()
            ->getResult();

    }

    function getQueryForSelect(): Query
    {
        $dql = "SELECT ppt FROM Silecust\WebShop\Entity\PriceProductTax ppt";
        return $this->getEntityManager()->createQuery($dql);

    }

    public function create(Product $product, TaxSlab $taxSlab): PriceProductTax
    {
        $tax = new PriceProductTax();
        $tax->setProduct($product);
        $tax->setTaxSlab($taxSlab);

        return $tax;

    }
}
