<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryImage>
 */
class CategoryImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryImage::class);
    }

    //    /**
    //     * @return CategoryImage[] Returns an array of CategoryImage objects
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

    //    public function findOneBySomeField($value): ?CategoryImage
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(Category $category, File $file):CategoryImage
    {
        $categoryImage = new CategoryImage();
        $categoryImage->setCategory($category);
        $categoryImage->setFile($file);

        return $categoryImage;

    }
}
