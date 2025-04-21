<?php

namespace Silecust\WebShop\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Silecust\WebShop\Service\Component\Database\Repository\SearchableRepository;

/**
 * @extends ServiceEntityRepository<ProductImage>
 */
class ProductImageRepository extends ServiceEntityRepository implements SearchableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    //    /**
    //     * @return ProductImage[] Returns an array of ProductImage objects
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

    //    public function findOneBySomeField($value): ?ProductImage
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(Product $product, File $file):ProductImage
    {
        $productImage = new ProductImage();
        $productImage->setProduct($product);
        $productImage->setFile($file);

        return $productImage;

    }
    /**
     * @throws QueryException
     */
    function getQueryForSelect(Criteria $criteria = null): Query
    {

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('pi')
            ->from(ProductImage::class, 'pi');

        if ($criteria != null) {
            $qb->addCriteria($criteria);
        }
        return $qb->getQuery();

    }
}
