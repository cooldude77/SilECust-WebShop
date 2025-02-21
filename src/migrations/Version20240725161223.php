<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725161223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_item_payment_price (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, base_price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_8D805786E415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_item_payment_price ADD CONSTRAINT FK_8D805786E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id)');
        $this->addSql('ALTER TABLE tax_base_product DROP FOREIGN KEY FK_777C1E514584665A');
        $this->addSql('ALTER TABLE tax_base_product DROP FOREIGN KEY FK_777C1E5138248176');
        $this->addSql('DROP TABLE tax_base_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tax_base_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, tax_rate DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_777C1E514584665A (product_id), INDEX IDX_777C1E5138248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tax_base_product ADD CONSTRAINT FK_777C1E514584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE tax_base_product ADD CONSTRAINT FK_777C1E5138248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE order_item_payment_price DROP FOREIGN KEY FK_8D805786E415FB15');
        $this->addSql('DROP TABLE order_item_payment_price');
    }
}
