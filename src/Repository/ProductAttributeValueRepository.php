<?php

namespace Silecust\WebShop\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use Silecust\WebShop\Entity\ProductAttribute;
use Silecust\WebShop\Entity\ProductAttributeValue;
use Silecust\WebShop\Service\Component\Database\Repository\SearchableRepository;

/**
 * @extends ServiceEntityRepository<ProductAttributeValue>
 *
 * @method ProductAttributeValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAttributeValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAttributeValue[]    findAll()
 * @method ProductAttributeValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAttributeValueRepository extends ServiceEntityRepository implements SearchableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAttributeValue::class);
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
    public function create(ProductAttribute $productAttribute): ProductAttributeValue
    {

        $productAttributeValue = new ProductAttributeValue();
        $productAttributeValue->setProductAttribute($productAttribute);
        return $productAttributeValue;
    }

    /**
     * @throws QueryException
     */
    function getQueryForSelect(Criteria $criteria = null): Query
    {

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('pav')
            ->from(ProductAttributeValue::class, 'pav');

        if ($criteria != null) {
            $qb->addCriteria($criteria);
        }
        return $qb->getQuery();

    }
}
