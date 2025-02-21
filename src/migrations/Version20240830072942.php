<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830072942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item_payment_price DROP FOREIGN KEY FK_8D805786E415FB15');
        $this->addSql('ALTER TABLE order_item_payment_price ADD CONSTRAINT FK_8D805786E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item_payment_price DROP FOREIGN KEY FK_8D805786E415FB15');
        $this->addSql('ALTER TABLE order_item_payment_price ADD CONSTRAINT FK_8D805786E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id)');
    }
}
