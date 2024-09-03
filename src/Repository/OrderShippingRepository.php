<?php

namespace App\Repository;

use App\Entity\OrderHeader;
use App\Entity\OrderShipping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderShipping>
 */
class OrderShippingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderShipping::class);
    }

    //    /**
    //     * @return OrderShipping[] Returns an array of OrderShipping objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OrderShipping
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(OrderHeader $orderHeader, string $name, float $value, string $data): OrderShipping
    {
        $ship = new OrderShipping();
        $ship->setOrderHeader($orderHeader);
        $ship->setName($name);
        $ship->setValue($value);
        $ship->setData(json_decode($data));

        return $ship;
    }
}
