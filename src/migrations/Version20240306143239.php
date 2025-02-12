<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306143239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop_home_section ADD webshop_home_id INT NOT NULL');
        $this->addSql('ALTER TABLE webshop_home_section ADD CONSTRAINT FK_DA93F5EB7C7416D9 FOREIGN KEY (webshop_home_id) REFERENCES webshop_home (id)');
        $this->addSql('CREATE INDEX IDX_DA93F5EB7C7416D9 ON webshop_home_section (webshop_home_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop_home_section DROP FOREIGN KEY FK_DA93F5EB7C7416D9');
        $this->addSql('DROP INDEX IDX_DA93F5EB7C7416D9 ON webshop_home_section');
        $this->addSql('ALTER TABLE webshop_home_section DROP webshop_home_id');
    }
}
