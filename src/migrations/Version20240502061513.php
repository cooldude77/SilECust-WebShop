<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502061513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_type_attribute (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, product_attribute_id INT NOT NULL, INDEX IDX_1DD5D0C714959723 (product_type_id), INDEX IDX_1DD5D0C73B420C91 (product_attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C714959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C73B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA597614959723');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59762EEEF9F3');
        $this->addSql('DROP INDEX UNIQ_94DA59762EEEF9F3 ON product_attribute');
        $this->addSql('DROP INDEX IDX_94DA597614959723 ON product_attribute');
        $this->addSql('ALTER TABLE product_attribute ADD attribute_type_id INT NOT NULL, DROP product_type_id, DROP value_type_id');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59764ED0D557 FOREIGN KEY (attribute_type_id) REFERENCES product_attribute_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94DA59764ED0D557 ON product_attribute (attribute_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C714959723');
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C73B420C91');
        $this->addSql('DROP TABLE product_type_attribute');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59764ED0D557');
        $this->addSql('DROP INDEX UNIQ_94DA59764ED0D557 ON product_attribute');
        $this->addSql('ALTER TABLE product_attribute ADD value_type_id INT NOT NULL, CHANGE attribute_type_id product_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA597614959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59762EEEF9F3 FOREIGN KEY (value_type_id) REFERENCES product_attribute_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94DA59762EEEF9F3 ON product_attribute (value_type_id)');
        $this->addSql('CREATE INDEX IDX_94DA597614959723 ON product_attribute (product_type_id)');
    }
}
