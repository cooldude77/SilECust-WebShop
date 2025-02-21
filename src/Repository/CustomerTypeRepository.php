<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\CustomerType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerType>
 *
 * @method CustomerType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerType[]    findAll()
 * @method CustomerType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerType::class);
    }

    //    /**
    //     * @return CustomerType[] Returns an array of CustomerType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CustomerType
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
