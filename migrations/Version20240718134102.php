<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718134102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tax_slab ADD country_id INT NOT NULL');
        $this->addSql('ALTER TABLE tax_slab ADD CONSTRAINT FK_DAD6B740F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_DAD6B740F92F3E70 ON tax_slab (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tax_slab DROP FOREIGN KEY FK_DAD6B740F92F3E70');
        $this->addSql('DROP INDEX IDX_DAD6B740F92F3E70 ON tax_slab');
        $this->addSql('ALTER TABLE tax_slab DROP country_id');
    }
}
