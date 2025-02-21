<?php

namespace Silecust\WebShop\Tests;

use Silecust\WebShop\Entity\CategoryImageType;
use Silecust\WebShop\Entity\City;
use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Entity\FileBaseType;
use Silecust\WebShop\Entity\PostalCode;
use Silecust\WebShop\Entity\ProductImageType;
use Silecust\WebShop\Entity\Salutation;
use Silecust\WebShop\Entity\State;
use Silecust\WebShop\Entity\WebShopImageType;
use Silecust\WebShop\Tests\Utility\TestDatabaseTruncate;
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