<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\WebShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebShop>
 *
 * @method WebShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebShop[]    findAll()
 * @method WebShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebShop::class);
    }

    //    /**
    //     * @return WebShopHome[] Returns an array of WebShopHome objects
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

    //    public function findOneBySomeField($value): ?WebShopHome
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(): WebShop
    {
        return new WebShop();
    }

    public function getQueryForSelect(): Query
    {
        $dql = "SELECT w FROM Silecust\WebShop\Entity\WebShop w";
        return $this->getEntityManager()->createQuery($dql);

    }
}
