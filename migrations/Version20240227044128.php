<?php /** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227044128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(255) NOT NULL, country_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, symbol VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6956883FF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount_price_category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount_price_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, discount_percentage INT NOT NULL, INDEX IDX_1523047E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_base_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_139FD1D44584665A (product_id), UNIQUE INDEX UNIQ_139FD1D438248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_base_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, tax_rate INT NOT NULL, UNIQUE INDEX UNIQ_777C1E514584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE currency ADD CONSTRAINT FK_6956883FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE discount_price_product ADD CONSTRAINT FK_1523047E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_base_product ADD CONSTRAINT FK_139FD1D44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_base_product ADD CONSTRAINT FK_139FD1D438248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE tax_base_product ADD CONSTRAINT FK_777C1E514584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE currency DROP FOREIGN KEY FK_6956883FF92F3E70');
        $this->addSql('ALTER TABLE discount_price_product DROP FOREIGN KEY FK_1523047E4584665A');
        $this->addSql('ALTER TABLE price_base_product DROP FOREIGN KEY FK_139FD1D44584665A');
        $this->addSql('ALTER TABLE price_base_product DROP FOREIGN KEY FK_139FD1D438248176');
        $this->addSql('ALTER TABLE tax_base_product DROP FOREIGN KEY FK_777C1E514584665A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE discount_price_category');
        $this->addSql('DROP TABLE discount_price_product');
        $this->addSql('DROP TABLE price_base_product');
        $this->addSql('DROP TABLE tax_base_product');
    }
}
