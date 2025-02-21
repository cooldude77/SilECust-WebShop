<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\WebShop;
use Silecust\WebShop\Entity\WebShopFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebShopFile>
 *
 * @method WebShopFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebShopFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebShopFile[]    findAll()
 * @method WebShopFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebShopFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebShopFile::class);
    }

    //    /**
    //     * @return WebShopFile[] Returns an array of WebShopFile objects
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

    //    public function findOneBySomeField($value): ?WebShopFile
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function create(\Silecust\WebShop\Entity\File $file, WebShop $webShop): WebShopFile
    {
        $webShopFile =  new WebShopFile();
        $webShopFile->setFile($file);
        $webShopFile->setWebShop($webShop);

        return $webShopFile;
    }
}
