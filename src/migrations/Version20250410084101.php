<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410084101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs


        $this->addSql(<<<'SQL'
            CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, salutation_id INT DEFAULT NULL, user_id INT NOT NULL, phone_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, middle_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, given_name VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_81398E09A76ED395 (user_id), INDEX IDX_81398E092E5AD854 (salutation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE category_image (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, file_id INT NOT NULL, UNIQUE INDEX UNIQ_2D0E4B1693CB796C (file_id), INDEX IDX_2D0E4B1612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product_attribute_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product_type_attribute (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, product_attribute_id INT NOT NULL, INDEX IDX_1DD5D0C714959723 (product_type_id), INDEX IDX_1DD5D0C73B420C91 (product_attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, salutation_id INT DEFAULT NULL, user_id INT NOT NULL, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, middle_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, given_name VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_5D9F75A1A76ED395 (user_id), INDEX IDX_5D9F75A12E5AD854 (salutation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_2D5B02345D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, postal_code_id INT NOT NULL, line1 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, line2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, line3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D4E6F81BDBA6A61 (postal_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE tax_slab (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rate_of_tax DOUBLE PRECISION NOT NULL, INDEX IDX_DAD6B740F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, last_logged_in_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_A393D2FBF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_payment (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, payment_response JSON NOT NULL COMMENT '(DC2Type:json)', UNIQUE INDEX UNIQ_9B522D46927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE discount_price_category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE discount_price_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, discount_percentage INT NOT NULL, INDEX IDX_1523047E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_52EA1F09927E6420 (order_header_id), INDEX IDX_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_shipping (id INT AUTO_INCREMENT NOT NULL, order_header_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, value DOUBLE PRECISION NOT NULL, data JSON NOT NULL COMMENT '(DC2Type:json)', INDEX IDX_CCE4F595927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE price_product_tax (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, tax_slab_id INT NOT NULL, UNIQUE INDEX UNIQ_6977A27F4584665A (product_id), INDEX IDX_6977A27FD2EF07B4 (tax_slab_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_header (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, order_status_type_id INT NOT NULL, date_time_of_order DATE NOT NULL, generated_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_ADFDB814A5EC2BA6 (order_status_type_id), INDEX IDX_ADFDB8149395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE price_product_base (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_B98FB0DD38248176 (currency_id), INDEX IDX_B98FB0DD4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE salutation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE customer_address (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, postal_code_id INT NOT NULL, line1 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, line2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, line3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, address_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_default TINYINT(1) DEFAULT NULL, INDEX IDX_1193CB3FBDBA6A61 (postal_code_id), INDEX IDX_1193CB3F9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, your_file_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE payment_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, symbol VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_6956883FF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_address (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, shipping_address_id INT DEFAULT NULL, billing_address_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_FB34C6CA79D0C0E4 (billing_address_id), INDEX IDX_FB34C6CA927E6420 (order_header_id), UNIQUE INDEX UNIQ_FB34C6CA4D4CFF2B (shipping_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_status_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_guid (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE price_product_discount (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_D92BC3464584665A (product_id), INDEX IDX_D92BC34638248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, product_attribute_type_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_94DA5976FA55E704 (product_attribute_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_64617F034584665A (product_id), UNIQUE INDEX UNIQ_64617F0393CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_item_payment_price (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, base_price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_8D805786E415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product_attribute_value (id INT AUTO_INCREMENT NOT NULL, product_attribute_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CCC4BE1F3B420C91 (product_attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_journal (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, order_snap_shot JSON NOT NULL COMMENT '(DC2Type:json)', created_at DATETIME NOT NULL, INDEX IDX_37DD4E06927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, path LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_64C19C1B548B0F (path), INDEX FK_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE postal_code (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_EA98E3768BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, order_status_type_id INT NOT NULL, date_of_status_set DATE NOT NULL, note LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, snap_shot LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT '(DC2Type:object)', UNIQUE INDEX UNIQ_B88F75C9A5EC2BA6 (order_status_type_id), INDEX IDX_B88F75C9927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(5000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_active TINYINT(1) NOT NULL, long_description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04ADC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE inventory_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_924EA2514584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );


        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hashed_token VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL
        );

        $this->addSql('INSERT INTO product_attribute_type (name, description)VALUES ("SINGLE_SELECT","Single Select")');
        $this->addSql('INSERT INTO product_attribute_type (name,description)VALUES ("MULTI_SELECT","Multi Select")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_CREATED","Order Created")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_PAYMENT_COMPLETE","Order Payment Complete")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_PAYMENT_FAILED","Order Payment Failed")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_IN_PROCESS","Order In Process")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_SHIPPED","Order Shipped")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_COMPLETED","Order Completed")');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs


        $this->addSql(<<<'SQL'
            DROP TABLE customer
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE category_image
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product_attribute_type
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product_type_attribute
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE employee
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE city
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE tax_slab
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE state
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_payment
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE discount_price_category
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE discount_price_product
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_item
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_shipping
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE price_product_tax
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_header
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE price_product_base
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE salutation
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product_type
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE customer_address
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE file
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE payment_type
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE currency
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_address
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_status_type
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_guid
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE price_product_discount
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product_attribute
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product_image
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE country
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_item_payment_price
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product_attribute_value
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_journal
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE postal_code
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE order_status
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE inventory_product
        SQL
        );


        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL
        );
    }
}
