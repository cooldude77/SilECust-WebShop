<?php

namespace App\Repository;

use App\Entity\ProductAttributeKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductAttributeKey>
 *
 * @method ProductAttributeKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAttributeKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAttributeKey[]    findAll()
 * @method ProductAttributeKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAttributeKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAttributeKey::class);
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
    public function create():ProductAttributeKey
    {
        return new ProductAttributeKey();
    }
}
