<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510060400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610C54C8C93');
        $this->addSql('ALTER TABLE product_file DROP FOREIGN KEY FK_17714B14584665A');
        $this->addSql('ALTER TABLE product_file DROP FOREIGN KEY FK_17714B193CB796C');
        $this->addSql('ALTER TABLE category_image_file DROP FOREIGN KEY FK_58BB8CF04E87FE04');
        $this->addSql('ALTER TABLE category_image_file DROP FOREIGN KEY FK_58BB8CF054133912');
        $this->addSql('ALTER TABLE category_file DROP FOREIGN KEY FK_7044C5793CB796C');
        $this->addSql('ALTER TABLE category_file DROP FOREIGN KEY FK_7044C5712469DE2');
        $this->addSql('ALTER TABLE product_image_file DROP FOREIGN KEY FK_EDC283A58AB1C7BD');
        $this->addSql('ALTER TABLE product_image_file DROP FOREIGN KEY FK_EDC283A5421262DC');
        $this->addSql('ALTER TABLE file_type DROP FOREIGN KEY FK_5223F47F14A35F7');
        $this->addSql('DROP TABLE file_base_type');
        $this->addSql('DROP TABLE product_file');
        $this->addSql('DROP TABLE category_image_type');
        $this->addSql('DROP TABLE category_image_file');
        $this->addSql('DROP TABLE category_file');
        $this->addSql('DROP TABLE product_image_file');
        $this->addSql('DROP TABLE product_image_type');
        $this->addSql('DROP TABLE file_type');
        $this->addSql('DROP INDEX IDX_8C9F3610C54C8C93 ON file');
        $this->addSql('ALTER TABLE file DROP type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file_base_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_file (id INT AUTO_INCREMENT NOT NULL, file_id INT NOT NULL, product_id INT NOT NULL, UNIQUE INDEX UNIQ_17714B193CB796C (file_id), INDEX IDX_17714B14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category_image_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, min_width INT NOT NULL, min_height INT NOT NULL, long_description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category_image_file (id INT AUTO_INCREMENT NOT NULL, category_file_id INT NOT NULL, category_image_type_id INT NOT NULL, UNIQUE INDEX UNIQ_58BB8CF054133912 (category_file_id), INDEX IDX_58BB8CF04E87FE04 (category_image_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category_file (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, file_id INT NOT NULL, UNIQUE INDEX UNIQ_7044C5793CB796C (file_id), INDEX IDX_7044C5712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_image_file (id INT AUTO_INCREMENT NOT NULL, product_file_id INT NOT NULL, product_image_type_id INT NOT NULL, INDEX IDX_EDC283A58AB1C7BD (product_image_type_id), UNIQUE INDEX UNIQ_EDC283A5421262DC (product_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_image_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, long_description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, min_width INT NOT NULL, min_height INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE file_type (id INT AUTO_INCREMENT NOT NULL, base_type_id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, mime_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_5223F47F14A35F7 (base_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_file ADD CONSTRAINT FK_17714B14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_file ADD CONSTRAINT FK_17714B193CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE category_image_file ADD CONSTRAINT FK_58BB8CF04E87FE04 FOREIGN KEY (category_image_type_id) REFERENCES category_image_type (id)');
        $this->addSql('ALTER TABLE category_image_file ADD CONSTRAINT FK_58BB8CF054133912 FOREIGN KEY (category_file_id) REFERENCES category_file (id)');
        $this->addSql('ALTER TABLE category_file ADD CONSTRAINT FK_7044C5793CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE category_file ADD CONSTRAINT FK_7044C5712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_image_file ADD CONSTRAINT FK_EDC283A58AB1C7BD FOREIGN KEY (product_image_type_id) REFERENCES product_image_type (id)');
        $this->addSql('ALTER TABLE product_image_file ADD CONSTRAINT FK_EDC283A5421262DC FOREIGN KEY (product_file_id) REFERENCES product_file (id)');
        $this->addSql('ALTER TABLE file_type ADD CONSTRAINT FK_5223F47F14A35F7 FOREIGN KEY (base_type_id) REFERENCES file_base_type (id)');
        $this->addSql('ALTER TABLE file ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610C54C8C93 FOREIGN KEY (type_id) REFERENCES file_type (id)');
        $this->addSql('CREATE INDEX IDX_8C9F3610C54C8C93 ON file (type_id)');
    }
}
