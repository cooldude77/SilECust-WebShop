<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612051124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_status DROP INDEX UNIQ_B88F75C9A5EC2BA6, ADD INDEX IDX_B88F75C9A5EC2BA6 (order_status_type_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_status DROP INDEX IDX_B88F75C9A5EC2BA6, ADD UNIQUE INDEX UNIQ_B88F75C9A5EC2BA6 (order_status_type_id)
        SQL);
    }
}
