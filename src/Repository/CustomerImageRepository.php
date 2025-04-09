<?php

namespace Silecust\WebShop\Repository;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerImage;
use Silecust\WebShop\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerImage>
 */
class CustomerImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerImage::class);
    }

    //    /**
    //     * @return CustomerImage[] Returns an array of CustomerImage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQuerryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CustomerImage
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(Customer $customer, File $file):CustomerImage
    {
        $customerImage = new CustomerImage();
        $customerImage->setCustomer($customer);
        $customerImage->setFile($file);

        return $customerImage;

    }
}
