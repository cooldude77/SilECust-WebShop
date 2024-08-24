<?php

namespace App\Repository;

use App\Entity\OrderItem;
use App\Entity\OrderItemPriceBreakup;
use App\Service\MasterData\Pricing\Item\PriceBreakUpObject;
use App\Service\Transaction\Order\PriceObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderItemPriceBreakup>
 *
 * @method OrderItemPriceBreakup|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItemPriceBreakup|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderItemPriceBreakup[]    findAll()
 * @method OrderItemPriceBreakup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderItemPriceBreakupRepository extends ServiceEntityRepository
{


    //    /**
    //     * @return OrderItemPriceBreakup[] Returns an array of OrderItemPriceBreakup objects
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

    //    public function findOneBySomeField($value): ?OrderItemPriceBreakup
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItemPriceBreakup::class);
    }

    public function create(OrderItem $orderItem, PriceObject $priceObject): OrderItemPriceBreakup
    {

        $breakup = new OrderItemPriceBreakup();

        $breakup->setOrderItem($orderItem);
        $breakup->setBasePrice($priceObject->getBasePrice());
        $breakup->setDiscount($priceObject->getDiscount());
        $breakup->setRateOfTax($priceObject->getTaxRate());

        return $breakup;
    }

    public function findAllByOrderHeader(\App\Entity\OrderHeader $orderHeader): mixed
    {

        return $this->getEntityManager()->createQueryBuilder('pb')
            ->select('pb')
            ->from('App\Entity\OrderItemPriceBreakup', 'pb')
            ->join('pb.orderItem', 'item')
            ->join('item.orderHeader', 'oh')
            ->where('oh=:oh')
            ->setParameter('oh', $orderHeader)
            ->getQuery()
            ->getResult();

    }
}
