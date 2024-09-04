<?php

namespace App\Repository;

use App\Entity\ProductAttributeKey;
use App\Entity\ProductAttributeKeyValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductAttributeKeyValue>
 *
 * @method ProductAttributeKeyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAttributeKeyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAttributeKeyValue[]    findAll()
 * @method ProductAttributeKeyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAttributeKeyValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAttributeKeyValue::class);
    }

    //    /**
    //     * @return ProductTypeAttributeValue[] Returns an array of ProductTypeAttributeValue objects
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

    //    public function findOneBySomeField($value): ?ProductTypeAttributeValue
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(ProductAttributeKey $productAttribute): ProductAttributeKeyValue
    {

        $productAttributeValue = new ProductAttributeKeyValue();
        $productAttributeValue->setProductAttributeKey($productAttribute);
        return $productAttributeValue;
    }
}
