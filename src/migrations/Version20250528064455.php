<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528064455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE discount_price_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_guid
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category RENAME INDEX fk_64c19c1727aca70 TO IDX_64C19C1727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_image ADD CONSTRAINT FK_2D0E4B1612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_image ADD CONSTRAINT FK_2D0E4B1693CB796C FOREIGN KEY (file_id) REFERENCES file (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city ADD CONSTRAINT FK_2D5B02345D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency ADD CONSTRAINT FK_6956883FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD CONSTRAINT FK_81398E092E5AD854 FOREIGN KEY (salutation_id) REFERENCES salutation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3FBDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES postal_code (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE discount_price_product ADD CONSTRAINT FK_1523047E4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A12E5AD854 FOREIGN KEY (salutation_id) REFERENCES salutation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_product ADD CONSTRAINT FK_924EA2514584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES customer_address (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES customer_address (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_header ADD CONSTRAINT FK_ADFDB8149395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_header ADD CONSTRAINT FK_ADFDB814A5EC2BA6 FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item_payment_price ADD CONSTRAINT FK_8D805786E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_journal ADD CONSTRAINT FK_37DD4E06927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_payment CHANGE payment_response payment_response VARCHAR(5000) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_payment ADD CONSTRAINT FK_9B522D46927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_shipping ADD CONSTRAINT FK_CCE4F595927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9A5EC2BA6 FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE postal_code ADD CONSTRAINT FK_EA98E3768BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_base ADD CONSTRAINT FK_B98FB0DD4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_base ADD CONSTRAINT FK_B98FB0DD38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_discount ADD CONSTRAINT FK_D92BC3464584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_discount ADD CONSTRAINT FK_D92BC34638248176 FOREIGN KEY (currency_id) REFERENCES currency (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_tax ADD CONSTRAINT FK_6977A27F4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_tax ADD CONSTRAINT FK_6977A27FD2EF07B4 FOREIGN KEY (tax_slab_id) REFERENCES tax_slab (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_id) REFERENCES product_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA5976FA55E704 FOREIGN KEY (product_attribute_type_id) REFERENCES product_attribute_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F3B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_image ADD CONSTRAINT FK_64617F0393CB796C FOREIGN KEY (file_id) REFERENCES file (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C714959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_type_attribute ADD CONSTRAINT FK_1DD5D0C73B420C91 FOREIGN KEY (product_attribute_id) REFERENCES product_attribute (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE state ADD CONSTRAINT FK_A393D2FBF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tax_slab ADD CONSTRAINT FK_DAD6B740F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE login login VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, postal_code_id INT NOT NULL, line1 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, line2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, line3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D4E6F81BDBA6A61 (postal_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE discount_price_category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_guid (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP FOREIGN KEY FK_81398E092E5AD854
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_image DROP FOREIGN KEY FK_2D0E4B1612469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_image DROP FOREIGN KEY FK_2D0E4B1693CB796C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C714959723
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_type_attribute DROP FOREIGN KEY FK_1DD5D0C73B420C91
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A12E5AD854
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city DROP FOREIGN KEY FK_2D5B02345D83CC1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tax_slab DROP FOREIGN KEY FK_DAD6B740F92F3E70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE login login VARCHAR(180) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE state DROP FOREIGN KEY FK_A393D2FBF92F3E70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_payment DROP FOREIGN KEY FK_9B522D46927E6420
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_payment CHANGE payment_response payment_response JSON NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE discount_price_product DROP FOREIGN KEY FK_1523047E4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09927E6420
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_shipping DROP FOREIGN KEY FK_CCE4F595927E6420
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_tax DROP FOREIGN KEY FK_6977A27F4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_tax DROP FOREIGN KEY FK_6977A27FD2EF07B4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_header DROP FOREIGN KEY FK_ADFDB8149395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_header DROP FOREIGN KEY FK_ADFDB814A5EC2BA6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_base DROP FOREIGN KEY FK_B98FB0DD4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_base DROP FOREIGN KEY FK_B98FB0DD38248176
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F9395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3FBDBA6A61
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency DROP FOREIGN KEY FK_6956883FF92F3E70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA927E6420
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA4D4CFF2B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA79D0C0E4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_discount DROP FOREIGN KEY FK_D92BC3464584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_product_discount DROP FOREIGN KEY FK_D92BC34638248176
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA5976FA55E704
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_image DROP FOREIGN KEY FK_64617F0393CB796C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item_payment_price DROP FOREIGN KEY FK_8D805786E415FB15
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F3B420C91
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_journal DROP FOREIGN KEY FK_37DD4E06927E6420
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category RENAME INDEX idx_64c19c1727aca70 TO FK_64C19C1727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE postal_code DROP FOREIGN KEY FK_EA98E3768BAC62AF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9927E6420
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9A5EC2BA6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC54C8C93
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_product DROP FOREIGN KEY FK_924EA2514584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
    }
}
