<?php

namespace App\Repository;

use App\Entity\OrderHeader;
use App\Entity\OrderStatus;
use App\Entity\OrderStatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderStatus>
 */
class OrderStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatus::class);
    }

    //    /**
    //     * @return OrderStatus[] Returns an array of OrderStatus objects
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

    //    public function findOneBySomeField($value): ?OrderStatus
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(OrderHeader $orderHeader, OrderStatusType $orderStatusType): OrderStatus
    {
        $orderStatus = new OrderStatus();

        $orderStatus->setOrderHeader($orderHeader);
        $orderStatus->setOrderStatusType($orderStatusType);

        return $orderStatus;

    }

    public function isAnyOrderOpen(\App\Entity\Customer $customer): bool
    {
        $query = $this->getOpenOrderQuery($customer);


        return $query->getResult() == null;

    }

    public function getOpenOrderQuery(\App\Entity\Customer $customer): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('os')
            ->join('os.orderHeader ', 'oh')
            ->where('oh.customer = :customer')

            ->groupBy('oh.id')
            ->having()
            ->setParameter("customer", $customer)
            ->getQuery();


    }

    public function getOpenOrder(\App\Entity\Customer $customer): OrderHeader
    {
        $query = $this->getOpenOrderQuery($customer);


        return $query->getResult();

    }

}
