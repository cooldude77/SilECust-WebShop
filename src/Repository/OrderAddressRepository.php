<?php

namespace App\Repository;

use App\Entity\CustomerAddress;
use App\Entity\OrderAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderAddress>
 *
 * @method OrderAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderAddress[]    findAll()
 * @method OrderAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderAddress::class);
    }

    //    /**
    //     * @return OrderAddress[] Returns an array of OrderAddress objects
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

    //    public function findOneBySomeField($value): ?OrderAddress
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(\App\Entity\OrderHeader $orderHeader,
        CustomerAddress $address
    ): OrderAddress {
        $orderAddress = new OrderAddress();

        $orderAddress->setOrderHeader($orderHeader);

        if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING) {
            $orderAddress->setShippingAddress($address);
        } else if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING)
            $orderAddress->setBillingAddress($address);

            return $orderAddress;
    }
}
