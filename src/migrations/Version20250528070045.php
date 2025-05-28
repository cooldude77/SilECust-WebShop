<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528070045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP INDEX UNIQ_FB34C6CA79D0C0E4, ADD INDEX IDX_FB34C6CA79D0C0E4 (billing_address_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP INDEX UNIQ_FB34C6CA4D4CFF2B, ADD INDEX IDX_FB34C6CA4D4CFF2B (shipping_address_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP INDEX IDX_FB34C6CA927E6420, ADD UNIQUE INDEX UNIQ_FB34C6CA927E6420 (order_header_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP INDEX UNIQ_FB34C6CA927E6420, ADD INDEX IDX_FB34C6CA927E6420 (order_header_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP INDEX IDX_FB34C6CA4D4CFF2B, ADD UNIQUE INDEX UNIQ_FB34C6CA4D4CFF2B (shipping_address_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP INDEX IDX_FB34C6CA79D0C0E4, ADD UNIQUE INDEX UNIQ_FB34C6CA79D0C0E4 (billing_address_id)
        SQL);
    }
}
