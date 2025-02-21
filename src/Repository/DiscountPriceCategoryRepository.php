<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\DiscountPriceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscountPriceCategory>
 *
 * @method DiscountPriceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountPriceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountPriceCategory[]    findAll()
 * @method DiscountPriceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountPriceCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountPriceCategory::class);
    }

//    /**
//     * @return DiscountPriceCategory[] Returns an array of DiscountPriceCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiscountPriceCategory
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
