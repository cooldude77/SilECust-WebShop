<?php

namespace Silecust\WebShop\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\QueryException;
use Silecust\WebShop\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Service\Component\Database\Repository\SearchableRepository;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository implements SearchableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
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

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(): Category
    {
        return new Category();
    }

    public function findTopLevelCategories(): array
    {

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c','parent')
            ->from(Category::class, 'c')
            ->join('c.parent','parent')
            ->orderBy('c.parent')
            ->getQuery()
            ->getResult();

    }

    /*
    return $this->getEntityManager()
        ->createQuery("SELECT a FROM Silecust\WebShop\Entity\Category  a where a.parent IS null")
        ->getResult();


     $p = $this->getEntityManager()
         ->createQueryBuilder()
         ->select('c', 'parent')
         //            ->addSelect('parent')
         ->from(Category::class, 'c')
         ->leftJoin('c.parent', 'parent')
         ->where('c.id = :id')
         ->setParameter('id',4)
         ->getQuery()
         ->getResult();


    $rsm = new ResultSetMapping();
    $rsm->addEntityResult(Category::class, 'c');
    $rsm->addFieldResult('c', 'id', 'id');
    //$rsm->addFieldResult('c', 'parent', 'parent');
    $rsm->addEntityResult(Category::class,'parent','parent');
    $rsm->addFieldResult('c', 'name', 'name');

    $query = $this->getEntityManager()->createNativeQuery('  with recursive cte as (
(  SELECT c.id, c.parent_id, c.name,1 AS level
FROM   category c
WHERE c.parent_id is null
)

UNION  ALL

(SELECT e.id, e.parent_id, e.name, cte.level + 1
FROM   cte cte
JOIN   category e ON e.parent_id= cte.id))

SELECT * FROM   cte;', $rsm);
//     ->setParameter(1, null);
    $x = $query->getResult();

    return $x;

//        return $p;

*/


    public function findAllCategories(): array
    {
        return $this->getEntityManager()
            ->createQuery("SELECT a FROM Silecust\WebShop\Entity\Category a")
            ->getResult(Query::HYDRATE_ARRAY);
        /*
         * select * from (SELECT cp.id,cp.name,cp.parent_id,cp.description
    FROM category AS cp JOIN category AS c
      ON cp.id = c.parent_id
UNION
SELECT cp.id,cp.name,cp.parent_id,cp.description
    FROM category cp ) x order by parent_id,id;
         */
    }

    public function findAllCategoriesTill(?Category $category)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $result = $qb->select('c')
            ->from(Category::class, 'c')
            ->where("c.path LIKE :p1")
            ->setParameter('p1', $category->getPath() . "/%")
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * @throws QueryException
     */
    function getQueryForSelect(Criteria $criteria = null): Query
    {

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c');

        if ($criteria != null) {
            $qb->addCriteria($criteria);
        }
        return $qb->getQuery();

    }
}
