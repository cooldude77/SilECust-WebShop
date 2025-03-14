<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderPayment>
 */
class OrderPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPayment::class);
    }

    //    /**
    //     * @return OrderPayment[] Returns an array of OrderPayment objects
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

    //    public function findOneBySomeField($value): ?OrderPayment
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(OrderHeader $orderHeader, array $paymentGatewayResponse): OrderPayment
    {
        $payment = new OrderPayment();
        $payment->setOrderHeader($orderHeader);
        $payment->setPaymentResponse($paymentGatewayResponse);

        return $payment;
    }
}
