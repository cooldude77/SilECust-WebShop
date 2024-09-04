<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904055937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_attribute_key_type CHANGE name type VARCHAR(255) NOT NULL');
        $this->addSql(  'INSERT INTO `product_attribute_key_type` (`type`, `description`)VALUES ("SINGLE_SELECT","Single Select")');
        $this->addSql(  'INSERT INTO `product_attribute_key_type` (`type`, `description`)VALUES ("MULTI_SELECT","Multi Select")');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_attribute_key_type CHANGE type name VARCHAR(255) NOT NULL');
    }
}
