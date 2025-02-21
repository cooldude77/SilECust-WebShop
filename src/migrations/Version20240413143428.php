<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413143428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_type ADD mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE file_type set mime_type = "image/jpg" where type="JPEG"');
        $this->addSql('ALTER TABLE file_type MODIFY mime_type VARCHAR(255) NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_type DROP mime_type');
    }
}
