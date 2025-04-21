<?php

namespace Silecust\WebShop\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Entity\ProductType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Silecust\WebShop\Service\Component\Database\Repository\SearchableRepository;

/**
 * @extends ServiceEntityRepository<ProductType>
 *
 * @method ProductType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductType[]    findAll()
 * @method ProductType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTypeRepository extends ServiceEntityRepository implements SearchableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductType::class);
    }

    //    /**
    //     * @return ProductType[] Returns an array of ProductType objects
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

    //    public function findOneBySomeField($value): ?ProductType
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create():ProductType
    {
        return new ProductType();
    }
    /**
     * @throws QueryException
     */
    function getQueryForSelect(Criteria $criteria = null): Query
    {

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('pt')
            ->from(ProductType::class, 'pt');

        if ($criteria != null) {
            $qb->addCriteria($criteria);
        }
        return $qb->getQuery();

    }
}
