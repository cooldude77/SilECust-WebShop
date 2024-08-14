<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(Category $category): Product
    {

        $product = new Product();
        $product->setCategory($category);
        return $product;
    }


    function getQueryForSelect(): Query
    {
        $dql = "SELECT p FROM App\Entity\Product p";
        return $this->getEntityManager()->createQuery($dql);

    }

    public function search(mixed $searchTerm)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $q = $qb->select('p')
            ->from(Product::class, 'p')
            ->where(
                $qb->expr()->like('p.name', ':searchTerm')
            )
            ->orWhere(
                $qb->expr()->like('p.description', ':searchTerm')
            )
            ->orWhere(

                $qb->expr()->like('p.longDescription', ':searchTerm')
            )
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        $e = $q->getDQL();

        return $q->getResult();
    }
}
