<?php /** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderJournal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderJournal>
 */
class OrderJournalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderJournal::class);
    }

    //    /**
    //     * @return OrderJournalRecorder[] Returns an array of OrderJournalRecorder objects
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

    //    public function findOneBySomeField($value): ?OrderJournalRecorder
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(OrderHeader $orderHeader): OrderJournal
    {
        $orderJournal = new OrderJournal();
        $orderJournal->setOrderHeader($orderHeader);

        return $orderJournal;
    }
}
