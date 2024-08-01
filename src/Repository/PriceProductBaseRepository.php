<?php

namespace App\Repository;

use App\Entity\PriceProductBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PriceProductBase>
 *
 * @method PriceProductBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceProductBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceProductBase[]    findAll()
 * @method PriceProductBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceProductBaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceProductBase::class);
    }

    //    /**
    //     * @return PriceBaseProduct[] Returns an array of PriceBaseProduct objects
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

    //    public function findOneBySomeField($value): ?PriceBaseProduct
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(?\App\Entity\Product $product, ?\App\Entity\Currency $currency): PriceProductBase
    {

        $price =  new PriceProductBase();
        $price->setProduct($product);
        $price->setCurrency($currency);
        return $price;
    }

    function getQueryForSelect(): Query
    {
        $dql = "SELECT ppb FROM App\Entity\PriceProductBase ppb";
        return $this->getEntityManager()->createQuery($dql);

    }
}
