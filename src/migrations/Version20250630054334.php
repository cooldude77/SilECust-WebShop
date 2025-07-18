<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630054334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09927E6420');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ORDER_HEADER_PRODUCT ON order_item (order_header_id, product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09927E6420');
        $this->addSql('DROP INDEX UNIQ_ORDER_HEADER_PRODUCT ON order_item');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
    }
}
