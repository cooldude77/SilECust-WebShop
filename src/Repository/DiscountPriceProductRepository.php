<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\DiscountPriceProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscountPriceProduct>
 *
 * @method DiscountPriceProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountPriceProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountPriceProduct[]    findAll()
 * @method DiscountPriceProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountPriceProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountPriceProduct::class);
    }

    //    /**
    //     * @return DiscountPriceProduct[] Returns an array of DiscountPriceProduct objects
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

    //    public function findOneBySomeField($value): ?DiscountPriceProduct
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
