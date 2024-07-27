<?php

namespace App\Repository;

use App\Entity\PriceProductTax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findProductByTaxSlabs(\App\Entity\Product $product, array $taxSlabs): mixed
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('ppt')
            ->from(PriceProductTax::class,'ppt')
            ->where('ppt.product =:product')
            ->andWhere("ppt.taxSlab IN  (:taxSlabs)")
            ->setParameter('product',$product)
            ->setParameter('taxSlabs',$taxSlabs)
            ->getQuery()
            ->getResult();

    }
}
