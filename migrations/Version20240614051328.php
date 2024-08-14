<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614051328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_header ADD order_status_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_header ADD CONSTRAINT FK_ADFDB814A5EC2BA6 FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('CREATE INDEX IDX_ADFDB814A5EC2BA6 ON order_header (order_status_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_header DROP FOREIGN KEY FK_ADFDB814A5EC2BA6');
        $this->addSql('DROP INDEX IDX_ADFDB814A5EC2BA6 ON order_header');
        $this->addSql('ALTER TABLE order_header DROP order_status_type_id');
    }
}
