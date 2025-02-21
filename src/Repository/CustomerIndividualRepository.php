<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\CustomerIndividual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerIndividual>
 *
 * @method CustomerIndividual|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerIndividual|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerIndividual[]    findAll()
 * @method CustomerIndividual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerIndividualRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerIndividual::class);
    }

    //    /**
    //     * @return CustomerIndividual[] Returns an array of CustomerIndividual objects
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

    //    public function findOneBySomeField($value): ?CustomerIndividual
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
