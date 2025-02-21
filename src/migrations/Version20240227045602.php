<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227045602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_header (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, date_time_of_order DATE NOT NULL, UNIQUE INDEX UNIQ_ADFDB8149395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_52EA1F09927E6420 (order_header_id), UNIQUE INDEX UNIQ_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item_price_breakup (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, UNIQUE INDEX UNIQ_280F15DCE415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_header ADD CONSTRAINT FK_ADFDB8149395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item_price_breakup ADD CONSTRAINT FK_280F15DCE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_header DROP FOREIGN KEY FK_ADFDB8149395C3F3');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09927E6420');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE order_item_price_breakup DROP FOREIGN KEY FK_280F15DCE415FB15');
        $this->addSql('DROP TABLE order_header');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE order_item_price_breakup');
    }
}
