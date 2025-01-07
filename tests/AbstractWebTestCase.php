<?php

namespace App\Tests;

use App\Entity\CategoryImageType;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\FileBaseType;
use App\Entity\PostalCode;
use App\Entity\ProductImageType;
use App\Entity\Salutation;
use App\Entity\State;
use App\Entity\WebShopImageType;
use App\Tests\Utility\TestDatabaseTruncate;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * This class was used before using DAMA Doctrine bundle
 * Kept here as reference , may be deleted later
 */
class AbstractWebTestCase extends WebTestCase
{
    use TestDatabaseTruncate;

    private EntityManager $entityManager;

    private array $doNotTruncateTablesWithClassName
        = array(FileBaseType::class,
                CategoryImageType::class,
                ProductImageType::class,
                Currency::class,
                Country::class,
                State::class,
                City::class,
                PostalCode::class,
                Salutation::class,
                WebShopImageType::class

        );

    public function setUp(): void
    {
        parent::setUp();
        $this->entityManager =$this->getContainer()->get('doctrine')->getManager();

        $list = $this->convertToTableList($this->doNotTruncateTablesWithClassName);
        $this->truncateDatabase($this->entityManager->getConnection(),$list);

    }

    private function convertToTableList(array $doNotTruncateTablesWithClassName): array
    {
        $tables = array();
        foreach ($doNotTruncateTablesWithClassName as $className) {
            $tables[] = $this->entityManager
                ->getClassMetadata(str_replace('APP\ENTITY',"",$className))
                ->getTableName();
        }
        return $tables;
    }
}