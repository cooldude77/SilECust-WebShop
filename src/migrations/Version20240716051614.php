<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716051614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price_product_tax (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, tax_slab_id INT NOT NULL, UNIQUE INDEX UNIQ_6977A27F4584665A (product_id), INDEX IDX_6977A27FD2EF07B4 (tax_slab_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_slab (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, rate_of_tax DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE price_product_tax ADD CONSTRAINT FK_6977A27F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_product_tax ADD CONSTRAINT FK_6977A27FD2EF07B4 FOREIGN KEY (tax_slab_id) REFERENCES tax_slab (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price_product_tax DROP FOREIGN KEY FK_6977A27F4584665A');
        $this->addSql('ALTER TABLE price_product_tax DROP FOREIGN KEY FK_6977A27FD2EF07B4');
        $this->addSql('DROP TABLE price_product_tax');
        $this->addSql('DROP TABLE tax_slab');
    }
}
