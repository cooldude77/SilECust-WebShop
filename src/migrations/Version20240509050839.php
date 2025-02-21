<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509050839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price_base (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_BDE60CDB4584665A (product_id), UNIQUE INDEX UNIQ_BDE60CDB38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE price_base ADD CONSTRAINT FK_BDE60CDB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_base ADD CONSTRAINT FK_BDE60CDB38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE price_base_product DROP FOREIGN KEY FK_139FD1D44584665A');
        $this->addSql('ALTER TABLE price_base_product DROP FOREIGN KEY FK_139FD1D438248176');
        $this->addSql('DROP TABLE price_base_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price_base_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_139FD1D44584665A (product_id), UNIQUE INDEX UNIQ_139FD1D438248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE price_base_product ADD CONSTRAINT FK_139FD1D44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_base_product ADD CONSTRAINT FK_139FD1D438248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE price_base DROP FOREIGN KEY FK_BDE60CDB4584665A');
        $this->addSql('ALTER TABLE price_base DROP FOREIGN KEY FK_BDE60CDB38248176');
        $this->addSql('DROP TABLE price_base');
    }
}
