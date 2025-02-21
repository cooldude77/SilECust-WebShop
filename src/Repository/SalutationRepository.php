<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\Salutation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salutation>
 *
 * @method Salutation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salutation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salutation[]    findAll()
 * @method Salutation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalutationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salutation::class);
    }

    //    /**
    //     * @return Salutation[] Returns an array of Salutation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Salutation
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
