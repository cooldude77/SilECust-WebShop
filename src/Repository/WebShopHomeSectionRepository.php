<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\WebShopSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebShopSection>
 *
 * @method WebShopSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebShopSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebShopSection[]    findAll()
 * @method WebShopSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebShopHomeSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebShopSection::class);
    }

    //    /**
    //     * @return WebShopHomeSection[] Returns an array of WebShopHomeSection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WebShopHomeSection
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(?\Silecust\WebShop\Entity\WebShop $webShop): WebShopSection
    {
        $webShopSection = new WebShopSection();
        $webShopSection->setWebShop($webShop);
        return $webShopSection;
    }
}
