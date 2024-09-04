<?php

namespace App\Repository;

use App\Entity\ProductGroupAttributeKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductGroupAttributeKey>
 *
 * @method ProductGroupAttributeKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductGroupAttributeKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductGroupAttributeKey[]    findAll()
 * @method ProductGroupAttributeKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTypeAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductGroupAttributeKey::class);
    }

    //    /**
    //     * @return ProductGroupAttributeKey[] Returns an array of ProductGroupAttributeKey objects
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

    //    public function findOneBySomeField($value): ?ProductGroupAttributeKey
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
