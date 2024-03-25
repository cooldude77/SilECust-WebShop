<?php

namespace App\Repository;

use App\Entity\CategoryFile;
use App\Entity\CategoryImageFile;
use App\Entity\CategoryImageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryImageFile>
 *
 * @method CategoryImageFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryImageFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryImageFile[]    findAll()
 * @method CategoryImageFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryImageFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryImageFile::class);
    }

    //    /**
    //     * @return CategoryImageFile[] Returns an array of CategoryImageFile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CategoryImageFile
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(
        CategoryFile $categoryFileEntity,
                           CategoryImageType $categoryImageType)
    {

        $categoryImageEntity = new CategoryImageFile();
        $categoryImageEntity->setCategoryFile($categoryFileEntity);
        $categoryImageEntity->setCategoryImageType($categoryImageType);

        return $categoryImageEntity;
    }
}