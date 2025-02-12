<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502053232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
     $this->addSql(  'INSERT INTO `product_attribute_type` (`type`, `description`)VALUES ("SINGLE_SELECT","Single Select")');
     $this->addSql(  'INSERT INTO `product_attribute_type` (`type`, `description`)VALUES ("MULTI_SELECT","Multi Select")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
 // Todo: write the reverse statements
    }
}
