<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301063320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_address (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, address_id INT NOT NULL, is_default TINYINT(1) NOT NULL, INDEX IDX_1193CB3F9395C3F3 (customer_id), UNIQUE INDEX UNIQ_1193CB3FF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_value_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type_attribute_value (id INT AUTO_INCREMENT NOT NULL, attribute_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_17239C75B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3FF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE product_type_attribute_value ADD CONSTRAINT FK_17239C75B6E62EFA FOREIGN KEY (attribute_id) REFERENCES product_type_attribute (id)');
        $this->addSql('ALTER TABLE product_type_attribute ADD value_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C72EEEF9F3 FOREIGN KEY (value_type_id) REFERENCES product_attribute_value_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DD5D0C72EEEF9F3 ON product_type_attribute (value_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C72EEEF9F3');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F9395C3F3');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3FF5B7AF75');
        $this->addSql('ALTER TABLE product_type_attribute_value DROP FOREIGN KEY FK_17239C75B6E62EFA');
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('DROP TABLE product_attribute_value_type');
        $this->addSql('DROP TABLE product_type_attribute_value');
        $this->addSql('DROP INDEX UNIQ_1DD5D0C72EEEF9F3 ON product_type_attribute');
        $this->addSql('ALTER TABLE product_type_attribute DROP value_type_id');
    }
}
