<?php

namespace Silecust\WebShop\Service\Testing\Utility;

use Doctrine\DBAL\Connection;

/**
 * This class was used before using DAMA Doctrine bundle
 * Kept here as reference , may be deleted later
 */
trait TestDatabaseTruncate
{

    private function truncateDatabase(Connection $connection, array $doNotTruncateTheseTables): void
    {
        $databasePlatform = $connection->getDatabasePlatform();
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');

        $tables = $connection->createSchemaManager()->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if (!in_array($tableName, $doNotTruncateTheseTables)) {

                $query = $databasePlatform->getTruncateTableSQL($tableName);
                $connection->executeQuery($query);

            }
        }
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');

    }
}