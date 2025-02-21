<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402060835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_shop_file ADD file_id INT NOT NULL');
        $this->addSql('ALTER TABLE web_shop_file ADD CONSTRAINT FK_93C111BF93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93C111BF93CB796C ON web_shop_file (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_shop_file DROP FOREIGN KEY FK_93C111BF93CB796C');
        $this->addSql('DROP INDEX UNIQ_93C111BF93CB796C ON web_shop_file');
        $this->addSql('ALTER TABLE web_shop_file DROP file_id');
    }
}
