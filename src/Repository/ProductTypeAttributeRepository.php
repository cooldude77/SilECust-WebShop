<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\ProductTypeAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductTypeAttribute>
 *
 * @method ProductTypeAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductTypeAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductTypeAttribute[]    findAll()
 * @method ProductTypeAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTypeAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductTypeAttribute::class);
    }

    //    /**
    //     * @return ProductTypeAttribute[] Returns an array of ProductTypeAttribute objects
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

    //    public function findOneBySomeField($value): ?ProductTypeAttribute
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
