<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\WebShopImageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebShopImageType>
 *
 * @method WebShopImageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebShopImageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebShopImageType[]    findAll()
 * @method WebShopImageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebShopImageTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebShopImageType::class);
    }

//    /**
//     * @return WebShopImageType[] Returns an array of WebShopImageType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WebShopImageType
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
