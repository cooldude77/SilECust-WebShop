<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409054123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE customer_image (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_B0871649395C3F3 (customer_id), UNIQUE INDEX UNIQ_B08716493CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_image ADD CONSTRAINT FK_B0871649395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_image ADD CONSTRAINT FK_B08716493CB796C FOREIGN KEY (file_id) REFERENCES file (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_file DROP FOREIGN KEY FK_93C111BFA5B96A43
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_file DROP FOREIGN KEY FK_93C111BF93CB796C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81BDBA6A61
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_type_attribute DROP FOREIGN KEY FK_D7D19A21D991282D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_individual DROP FOREIGN KEY FK_7A5AF8952E5AD854
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_image_file DROP FOREIGN KEY FK_D9A6107D17F9ADB8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_image_file DROP FOREIGN KEY FK_D9A6107D1998DF99
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_section DROP FOREIGN KEY FK_A941AE3BA5B96A43
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE web_shop_image_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE web_shop_file
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer_type_attribute
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE discount_price_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer_individual
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE web_shop_image_file
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE web_shop_section
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_guid
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE web_shop
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP INDEX FK_64C19C1727ACA70, ADD UNIQUE INDEX UNIQ_64C19C1727ACA70 (parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE login login VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE web_shop_image_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, min_width INT NOT NULL, min_height INT NOT NULL, long_description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE web_shop_file (id INT AUTO_INCREMENT NOT NULL, web_shop_id INT NOT NULL, file_id INT NOT NULL, UNIQUE INDEX UNIQ_93C111BF93CB796C (file_id), INDEX IDX_93C111BFA5B96A43 (web_shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, postal_code_id INT NOT NULL, line1 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, line2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, line3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D4E6F81BDBA6A61 (postal_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer_type_attribute (id INT AUTO_INCREMENT NOT NULL, customer_type_id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D7D19A21D991282D (customer_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE discount_price_category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer_individual (id INT AUTO_INCREMENT NOT NULL, salutation_id INT DEFAULT NULL, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, middle_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_7A5AF8952E5AD854 (salutation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE web_shop_image_file (id INT AUTO_INCREMENT NOT NULL, web_shop_file_id INT NOT NULL, web_shop_image_type_id INT NOT NULL, INDEX IDX_D9A6107D1998DF99 (web_shop_image_type_id), UNIQUE INDEX UNIQ_D9A6107D17F9ADB8 (web_shop_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE web_shop_section (id INT AUTO_INCREMENT NOT NULL, web_shop_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, section_order INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_A941AE3BA5B96A43 (web_shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_guid (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE web_shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_file ADD CONSTRAINT FK_93C111BFA5B96A43 FOREIGN KEY (web_shop_id) REFERENCES web_shop (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_file ADD CONSTRAINT FK_93C111BF93CB796C FOREIGN KEY (file_id) REFERENCES file (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES postal_code (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_type_attribute ADD CONSTRAINT FK_D7D19A21D991282D FOREIGN KEY (customer_type_id) REFERENCES customer_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_individual ADD CONSTRAINT FK_7A5AF8952E5AD854 FOREIGN KEY (salutation_id) REFERENCES salutation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_image_file ADD CONSTRAINT FK_D9A6107D17F9ADB8 FOREIGN KEY (web_shop_file_id) REFERENCES web_shop_file (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_image_file ADD CONSTRAINT FK_D9A6107D1998DF99 FOREIGN KEY (web_shop_image_type_id) REFERENCES web_shop_image_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE web_shop_section ADD CONSTRAINT FK_A941AE3BA5B96A43 FOREIGN KEY (web_shop_id) REFERENCES web_shop (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_image DROP FOREIGN KEY FK_B0871649395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_image DROP FOREIGN KEY FK_B08716493CB796C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer_image
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE login login VARCHAR(180) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP INDEX UNIQ_64C19C1727ACA70, ADD INDEX FK_64C19C1727ACA70 (parent_id)
        SQL);
    }
}
