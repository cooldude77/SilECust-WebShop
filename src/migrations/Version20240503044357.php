<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503044357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59764ED0D557');
        $this->addSql('DROP INDEX UNIQ_94DA59764ED0D557 ON product_attribute');
        $this->addSql('ALTER TABLE product_attribute CHANGE attribute_type_id product_attribute_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA5976FA55E704 FOREIGN KEY (product_attribute_type_id) REFERENCES product_attribute_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94DA5976FA55E704 ON product_attribute (product_attribute_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA5976FA55E704');
        $this->addSql('DROP INDEX UNIQ_94DA5976FA55E704 ON product_attribute');
        $this->addSql('ALTER TABLE product_attribute CHANGE product_attribute_type_id attribute_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59764ED0D557 FOREIGN KEY (attribute_type_id) REFERENCES product_attribute_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94DA59764ED0D557 ON product_attribute (attribute_type_id)');
    }
}
