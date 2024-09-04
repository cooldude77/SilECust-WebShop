<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903160648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC54C8C93');
        $this->addSql('CREATE TABLE product_attribute_key (id INT AUTO_INCREMENT NOT NULL, product_attribute_key_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_667722E4EEE9D154 (product_attribute_key_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_key_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_key_value (id INT AUTO_INCREMENT NOT NULL, product_attribute_key_id INT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_1B29BEEFA1791F95 (product_attribute_key_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_group_attribute_key (id INT AUTO_INCREMENT NOT NULL, product_group_id INT NOT NULL, product_attribute_key_id INT NOT NULL, INDEX IDX_BA321BC635E4B3D0 (product_group_id), INDEX IDX_BA321BC6A1791F95 (product_attribute_key_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_attribute_key ADD CONSTRAINT FK_667722E4EEE9D154 FOREIGN KEY (product_attribute_key_type_id) REFERENCES product_attribute_key_type (id)');
        $this->addSql('ALTER TABLE product_attribute_key_value ADD CONSTRAINT FK_1B29BEEFA1791F95 FOREIGN KEY (product_attribute_key_id) REFERENCES product_attribute_key (id)');
        $this->addSql('ALTER TABLE product_group_attribute_key ADD CONSTRAINT FK_BA321BC635E4B3D0 FOREIGN KEY (product_group_id) REFERENCES product_group (id)');
        $this->addSql('ALTER TABLE product_group_attribute_key ADD CONSTRAINT FK_BA321BC6A1791F95 FOREIGN KEY (product_attribute_key_id) REFERENCES product_attribute_key (id)');
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C714959723');
        $this->addSql('ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C73B420C91');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA5976FA55E704');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F3B420C91');
        $this->addSql('DROP TABLE product_attribute_type');
        $this->addSql('DROP TABLE product_type_attribute');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE product_attribute');
        $this->addSql('DROP TABLE product_attribute_value');
        $this->addSql('DROP INDEX IDX_D34A04ADC54C8C93 ON product');
        $this->addSql('ALTER TABLE product CHANGE type_id product_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD35E4B3D0 FOREIGN KEY (product_group_id) REFERENCES product_group (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD35E4B3D0 ON product (product_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD35E4B3D0');
        $this->addSql('CREATE TABLE product_attribute_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_type_attribute (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, product_attribute_id INT NOT NULL, INDEX IDX_1DD5D0C714959723 (product_type_id), INDEX IDX_1DD5D0C73B420C91 (product_attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, product_attribute_type_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_94DA5976FA55E704 (product_attribute_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_attribute_value (id INT AUTO_INCREMENT NOT NULL, product_attribute_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CCC4BE1F3B420C91 (product_attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C714959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C73B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA5976FA55E704 FOREIGN KEY (product_attribute_type_id) REFERENCES product_attribute_type (id)');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F3B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('ALTER TABLE product_attribute_key DROP FOREIGN KEY FK_667722E4EEE9D154');
        $this->addSql('ALTER TABLE product_attribute_key_value DROP FOREIGN KEY FK_1B29BEEFA1791F95');
        $this->addSql('ALTER TABLE product_group_attribute_key DROP FOREIGN KEY FK_BA321BC635E4B3D0');
        $this->addSql('ALTER TABLE product_group_attribute_key DROP FOREIGN KEY FK_BA321BC6A1791F95');
        $this->addSql('DROP TABLE product_attribute_key');
        $this->addSql('DROP TABLE product_attribute_key_type');
        $this->addSql('DROP TABLE product_attribute_key_value');
        $this->addSql('DROP TABLE product_group');
        $this->addSql('DROP TABLE product_group_attribute_key');
        $this->addSql('DROP INDEX IDX_D34A04AD35E4B3D0 ON product');
        $this->addSql('ALTER TABLE product CHANGE product_group_id type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_id) REFERENCES product_type (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADC54C8C93 ON product (type_id)');
    }
}
