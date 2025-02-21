<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\PriceProductDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PriceProductDiscount>
 */
class PriceProductDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceProductDiscount::class);
    }

//    /**
//     * @return PriceProductDiscount[] Returns an array of PriceProductDiscount objects
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

//    public function findOneBySomeField($value): ?PriceProductDiscount
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function create(?\Silecust\WebShop\Entity\Product $product, ?\Silecust\WebShop\Entity\Currency $currency
    ): PriceProductDiscount {

        $discount = new PriceProductDiscount();
        $discount->setProduct($product);
        $discount->setCurrency($currency);
        return $discount;
    }


    function getQueryForSelect(): Query
    {
        $dql = "SELECT ppd FROM Silecust\WebShop\Entity\PriceProductDiscount ppd";
        return $this->getEntityManager()->createQuery($dql);

    }
}
