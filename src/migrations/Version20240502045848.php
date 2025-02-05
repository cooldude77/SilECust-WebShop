<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502045848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, value_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_94DA597614959723 (product_type_id), UNIQUE INDEX UNIQ_94DA59762EEEF9F3 (value_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_value (id INT AUTO_INCREMENT NOT NULL, attribute_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_CCC4BE1FB6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA597614959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59762EEEF9F3 FOREIGN KEY (value_type_id) REFERENCES product_attribute_type (id)');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C714959723');
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C72EEEF9F3');
        $this->addSql('ALTER TABLE product_type_attribute_value DROP FOREIGN KEY FK_17239C75B6E62EFA');
        $this->addSql('DROP TABLE product_type_attribute');
        $this->addSql('DROP TABLE product_attribute_value_type');
        $this->addSql('DROP TABLE product_type_attribute_value');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_type_attribute (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, value_type_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1DD5D0C714959723 (product_type_id), UNIQUE INDEX UNIQ_1DD5D0C72EEEF9F3 (value_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_attribute_value_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_type_attribute_value (id INT AUTO_INCREMENT NOT NULL, attribute_id INT NOT NULL, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_17239C75B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C714959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C72EEEF9F3 FOREIGN KEY (value_type_id) REFERENCES product_attribute_value_type (id)');
        $this->addSql('ALTER TABLE product_type_attribute_value ADD CONSTRAINT FK_17239C75B6E62EFA FOREIGN KEY (attribute_id) REFERENCES product_type_attribute (id)');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA597614959723');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59762EEEF9F3');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1FB6E62EFA');
        $this->addSql('DROP TABLE product_attribute');
        $this->addSql('DROP TABLE product_attribute_type');
        $this->addSql('DROP TABLE product_attribute_value');
    }
}
