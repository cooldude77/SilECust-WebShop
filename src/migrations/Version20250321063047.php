<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321063047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('ALTER TABLE postal_code CHANGE postal_code code VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE postal_code CHANGE code postal_code VARCHAR(255) NOT NULL');
    }
}
