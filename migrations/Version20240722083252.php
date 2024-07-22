<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722083252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item_price_breakup DROP FOREIGN KEY FK_280F15DCE415FB15');
        $this->addSql('ALTER TABLE order_item_price_breakup ADD CONSTRAINT FK_280F15DCE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item_price_breakup DROP FOREIGN KEY FK_280F15DCE415FB15');
        $this->addSql('ALTER TABLE order_item_price_breakup ADD CONSTRAINT FK_280F15DCE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id)');
    }
}
