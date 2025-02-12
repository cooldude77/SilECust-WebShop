<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830073154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item_price_breakup DROP FOREIGN KEY FK_280F15DCE415FB15');
        $this->addSql('DROP TABLE order_item_price_breakup');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_item_price_breakup (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, base_price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, rate_of_tax DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_280F15DCE415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_item_price_breakup ADD CONSTRAINT FK_280F15DCE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE');
    }
}
