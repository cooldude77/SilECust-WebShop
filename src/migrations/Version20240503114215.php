<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503114215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1FB6E62EFA');
        $this->addSql('DROP INDEX IDX_CCC4BE1FB6E62EFA ON product_attribute_value');
        $this->addSql('ALTER TABLE product_attribute_value CHANGE attribute_id product_attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F3B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('CREATE INDEX IDX_CCC4BE1F3B420C91 ON product_attribute_value (product_attribute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F3B420C91');
        $this->addSql('DROP INDEX IDX_CCC4BE1F3B420C91 ON product_attribute_value');
        $this->addSql('ALTER TABLE product_attribute_value CHANGE product_attribute_id attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('CREATE INDEX IDX_CCC4BE1FB6E62EFA ON product_attribute_value (attribute_id)');
    }
}
