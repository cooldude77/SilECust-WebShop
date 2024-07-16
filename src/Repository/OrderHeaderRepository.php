<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\OrderHeader;
use App\Entity\OrderStatusType;
use App\Service\Transaction\Order\Status\OrderStatusTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderHeader>
 *
 * @method OrderHeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderHeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderHeader[]    findAll()
 * @method OrderHeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderHeader::class);
    }

    //    /**
    //     * @return OrderHeader[] Returns an array of OrderHeader objects
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

    //    public function findOneBySomeField($value): ?OrderHeader
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(Customer $customer): \App\Entity\OrderHeader
    {

        $orderHeader = new OrderHeader();

        $orderHeader->setCustomer($customer);

        $orderHeader->setDateTimeOfOrder(new \DateTime());

        $orderStatusType = $this->getEntityManager()->getRepository(OrderStatusType::class)
            ->findOneBy(
                ['type' => OrderStatusTypes::ORDER_CREATED]
            );

        $orderHeader->setOrderStatusType($orderStatusType);

        return $orderHeader;

    }

    function getQueryForSelect(): Query
    {
        $dql = "SELECT oh FROM App\Entity\OrderHeader oh";
        return $this->getEntityManager()->createQuery($dql);

    }
}
