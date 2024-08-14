<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722083637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA927E6420');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA927E6420');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
    }
}
