<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715053527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE price_product_discount (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_D92BC3464584665A (product_id), INDEX IDX_D92BC34638248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE price_product_discount ADD CONSTRAINT FK_D92BC3464584665A FOREIGN KEY (product_id) REFERENCES product (id)'
        );
        $this->addSql(
            'ALTER TABLE price_product_discount ADD CONSTRAINT FK_D92BC34638248176 FOREIGN KEY (currency_id) REFERENCES currency (id)'
        );
        $this->addSql(
            'ALTER TABLE price_product_base ADD INDEX IDX_B98FB0DD4584665A (product_id)'
        );
        $this->addSql(
            'ALTER TABLE tax_base_product ADD currency_id INT NOT NULL, CHANGE tax_rate tax_rate DOUBLE PRECISION NOT NULL'
        );
        $this->addSql(
            'ALTER TABLE tax_base_product ADD CONSTRAINT FK_777C1E5138248176 FOREIGN KEY (currency_id) REFERENCES currency (id)'
        );
        $this->addSql('CREATE INDEX IDX_777C1E5138248176 ON tax_base_product (currency_id)');
        $this->addSql('ALTER TABLE tax_base_product MODIFY tax_rate float NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price_product_discount DROP FOREIGN KEY FK_D92BC3464584665A');
        $this->addSql('ALTER TABLE price_product_discount DROP FOREIGN KEY FK_D92BC34638248176');
        $this->addSql('DROP TABLE price_product_discount');
        $this->addSql(
            'ALTER TABLE price_product_base DROP INDEX IDX_B98FB0DD4584665A, ADD UNIQUE INDEX UNIQ_B98FB0DD4584665A (product_id)'
        );
        $this->addSql('ALTER TABLE tax_base_product DROP FOREIGN KEY FK_777C1E5138248176');
        $this->addSql('DROP INDEX IDX_777C1E5138248176 ON tax_base_product');
        $this->addSql(
            'ALTER TABLE tax_base_product DROP currency_id, CHANGE tax_rate tax_rate INT NOT NULL'
        );
    }
}
