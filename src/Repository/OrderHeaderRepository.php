<?php

namespace Silecust\WebShop\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderStatusType;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;

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
    public function create(Customer $customer): \Silecust\WebShop\Entity\OrderHeader
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
        $dql = "SELECT oh FROM Silecust\WebShop\Entity\OrderHeader oh";
        return $this->getEntityManager()->createQuery($dql);

    }

    function getQueryForSelectByCustomer(Customer $customer): Query
    {
        $dql = "SELECT oh FROM Silecust\WebShop\Entity\OrderHeader oh where oh.customer=:customer";
        return $this->getEntityManager()->createQuery($dql)->setParameter('customer', $customer);

    }

    public function getQueryForSelectAllButOpenOrders(Criteria $criteria): Query
    {

        return $this->getEntityManager()->createQueryBuilder()
            ->select('oh')
            ->from(OrderHeader::class, 'oh')
            ->join('oh.orderStatusType', 'ost')
            ->where('ost.type != :type')
            ->addCriteria($criteria)
            ->setParameter('type', OrderStatusTypes::ORDER_CREATED)
            ->getQuery();


    }
}
