<?php

namespace App\Repository;

use App\Entity\OrderHeader;
use App\Entity\OrderItem;
use App\Entity\OrderItemPaymentPrice;
use App\Service\Transaction\Order\PriceObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderItemPaymentPrice>
 */
class OrderItemPaymentPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItemPaymentPrice::class);
    }

    //    /**
    //     * @return OrderItemPaymentPrice[] Returns an array of OrderItemPaymentPrice objects
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

    //    public function findOneBySomeField($value): ?OrderItemPaymentPrice
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByOrderHeader(OrderHeader $orderHeader): mixed
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('ipp')
            ->from(OrderItemPaymentPrice::class, 'ipp')
            ->join('ipp.orderItem', 'oi')
            ->join('oi.orderHeader', 'oh')
            ->where('oh=:oh')
            ->setParameter('oh', $orderHeader)
            ->getQuery()
            ->getResult();
    }

    public function create(OrderItem   $orderItem,
                           PriceObject $priceObject
    ): OrderItemPaymentPrice
    {

        $orderItemPaymentPrice = new OrderItemPaymentPrice();
        $orderItemPaymentPrice->setOrderItem($orderItem);
        $orderItemPaymentPrice->setBasePrice($priceObject->getBasePrice());
        $orderItemPaymentPrice->setDiscount($priceObject->getDiscount());
        $orderItemPaymentPrice->setTaxRate($priceObject->getTaxRate());

        return $orderItemPaymentPrice;
    }
}
