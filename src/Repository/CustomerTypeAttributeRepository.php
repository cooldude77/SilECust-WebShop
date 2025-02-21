<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\CustomerTypeAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerTypeAttribute>
 *
 * @method CustomerTypeAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerTypeAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerTypeAttribute[]    findAll()
 * @method CustomerTypeAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerTypeAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerTypeAttribute::class);
    }

    //    /**
    //     * @return CustomerTypeAttribute[] Returns an array of CustomerTypeAttribute objects
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

    //    public function findOneBySomeField($value): ?CustomerTypeAttribute
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
